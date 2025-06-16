/**
 * Admin JWT Authentication Utilities
 * 
 * This file contains functions to handle JWT authentication in the admin panel.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on an admin page (excluding the login page)
    const isAdminPage = window.location.pathname.startsWith('/admin') && 
                       !window.location.pathname.includes('/admin/login');
    
    if (isAdminPage) {
        const token = localStorage.getItem('access_token');
        
        // If no token found, redirect to login
        if (!token) {
            window.location.href = '/admin/login';
            return;
        }
        
        // Verify token with a quick check to the API
        fetch('/api/auth/me', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Invalid token');
            }
            return response.json();
        })
        .catch(error => {
            console.error('Authentication error:', error);
            // Clear token and redirect to login
            localStorage.removeItem('access_token');
            window.location.href = '/admin/login';
        });
    }
    
    // If on login page and token exists, check if valid and redirect to dashboard
    const isLoginPage = window.location.pathname.includes('/admin/login');
    
    if (isLoginPage) {
        const token = localStorage.getItem('access_token');
        
        if (token) {
            fetch('/api/auth/me', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Invalid token');
                }
                return response.json();
            })
            .then(data => {
                // Redirect based on role
                if (data.role === 'kpri admin') {
                    window.location.href = '/admin/dashboard';
                } else if (data.role === 'admin shop') {
                    window.location.href = '/admin/shop-dashboard';
                } else {
                    window.location.href = '/admin';
                }
            })
            .catch(error => {
                console.error('Authentication error:', error);
                // Clear token since it's invalid
                localStorage.removeItem('access_token');
            });
        }
    }
}); 