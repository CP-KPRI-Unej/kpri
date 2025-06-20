<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KPRI Linktree</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Push notification script -->
    <script src="{{ asset('js/push-notifications.js') }}"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            color: white;
            min-height: 100vh;
            padding: 2rem;
            background-color: #000;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        
        .bg-overlay {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .content {
            max-width: 500px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 10;
        }
        
        .profile-img {
            width: 9rem;
            height: 9rem;
            border-radius: 50%;
            background-color: #f39c12;
            border: 4px solid #f39c12;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .profile-img img {
            width: 95%;
            height: 95%;
            object-fit: contain;
            border-radius: 50%;
        }
        
        .title {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            text-align: center;
        }
        
        .bio {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
            text-align: center;
            max-width: 500px;
        }
        
        .links {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .link {
            width: 100%;
            background-color: #f39c12;
            border-radius: 9999px;
            padding: 1rem 1.25rem;
            color: white;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
        }
        
        .link:hover {
            background-color: #e67e22;
            transform: scale(1.01);
        }
        
        .link:active {
            transform: scale(0.99);
        }
        
        .loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }
        
        .loading-spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid #f39c12;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error {
            color: #e74c3c;
            text-align: center;
            padding: 2rem;
        }
    </style>
    <script>
        // Simple notification permission request - only on root path
        document.addEventListener('DOMContentLoaded', function() {
            // Only request permission on the root path
            if (window.location.pathname === '/') {
                setTimeout(function() {
                    if ('Notification' in window) {
                        if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                            Notification.requestPermission().then(function(permission) {
                                if (permission === 'granted') {
                                    // Register service worker for push notifications if permission granted
                                    if ('serviceWorker' in navigator && 'PushManager' in window) {
                                        navigator.serviceWorker.register('/sw.js')
                                            .then(function(registration) {
                                                // Initialize push notification handler
                                                if (window.pushNotificationHandler) {
                                                    window.pushNotificationHandler.init()
                                                        .then(function(success) {
                                                            if (success) {
                                                                window.pushNotificationHandler.subscribe();
                                                            }
                                                        });
                                                }
                                            });
                                    }
                                }
                            });
                        }
                    }
                }, 2000); // Short delay for better user experience
            }
        });
    </script>
</head>
<body>
    <!-- Background image div -->
    <div class="bg-overlay" id="bg-overlay">
        <img src="{{ asset('images/bg_linktree.png') }}" alt="Background" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    
    <div class="content" id="content">
        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
            <p>Loading...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contentElement = document.getElementById('content');
            const loadingElement = document.getElementById('loading');
            
            // Check if an ID was passed from the route
            const linktreeId = "{{ $id ?? '' }}";
            const apiUrl = linktreeId ? `/api/linktree/${linktreeId}` : '/api/linktree';
            
            // Fetch linktree data from the API
            fetch(apiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        renderLinktree(data.data.linktree, data.data.links);
                    } else {
                        showError('Failed to load linktree data');
                    }
                })
                .catch(error => {
                    console.error('Error fetching linktree data:', error);
                    showError('Failed to load linktree data');
                });
            
            function renderLinktree(linktree, links) {
                // Remove loading indicator
                loadingElement.remove();
                
                // Create profile image
                const profileImg = document.createElement('div');
                profileImg.className = 'profile-img';
                
                const img = document.createElement('img');
                if (linktree.logo) {
                    img.src = `/storage/${linktree.logo}`;
                } else {
                    img.src = "{{ asset('images/fasilkom_logo.png') }}";
                }
                img.alt = linktree.title;
                
                profileImg.appendChild(img);
                contentElement.appendChild(profileImg);
                
                // Create title
                const title = document.createElement('h1');
                title.className = 'title';
                title.textContent = linktree.title;
                contentElement.appendChild(title);
                
                // Create bio if exists
                if (linktree.bio) {
                    const bio = document.createElement('p');
                    bio.className = 'bio';
                    bio.textContent = linktree.bio;
                    contentElement.appendChild(bio);
                }
                
                // Create links container
                const linksContainer = document.createElement('div');
                linksContainer.className = 'links';
                
                // Add links
                links.forEach(link => {
                    const linkElement = document.createElement('a');
                    linkElement.className = 'link';
                    linkElement.href = link.url;
                    linkElement.target = '_blank';
                    linkElement.rel = 'noopener noreferrer';
                    linkElement.textContent = link.title;
                    
                    linksContainer.appendChild(linkElement);
                });
                
                contentElement.appendChild(linksContainer);
                
                // Set page title
                document.title = linktree.title;
            }
            
            function showError(message) {
                // Remove loading indicator
                loadingElement.remove();
                
                // Create error message
                const errorElement = document.createElement('div');
                errorElement.className = 'error';
                errorElement.textContent = message;
                
                contentElement.appendChild(errorElement);
            }
        });
    </script>
</body>
</html>