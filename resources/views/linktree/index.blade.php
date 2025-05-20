<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $linktree->title }}</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    </style>
</head>
<body class="font-['Inter'] text-white flex flex-col items-center min-h-screen p-8 md:p-10 bg-black relative">
    <!-- Background image div -->
    <div class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat bg-fixed">
        <img src="{{ asset('images/bg_linktree.png') }}" alt="Background" class="w-full h-full object-cover">
    </div>
    
    <div class="max-w-md w-full flex flex-col items-center relative z-10">
        <!-- Logo -->
        <div class="w-36 h-36 rounded-full bg-[#f39c12] border-4 border-[#f39c12] flex items-center justify-center mb-6 overflow-hidden">
            @if($linktree->logo)
                <img src="{{ asset('storage/' . $linktree->logo) }}" alt="{{ $linktree->title }}" class="w-[95%] h-[95%] object-contain rounded-full">
            @else
                <img src="{{ asset('images/logo.png') }}" alt="{{ $linktree->title }}" class="w-[95%] h-[95%] object-contain rounded-full">
            @endif
        </div>
        
        <!-- Title -->
        <h1 class="text-3xl font-bold mb-3 text-center text-white">{{ $linktree->title }}</h1>
        
        <!-- Bio -->
        @if($linktree->bio)
            <p class="text-base text-white/80 mb-8 text-center max-w-[500px]">{{ $linktree->bio }}</p>
        @else
            <div class="mb-8"></div>
        @endif
        
        <!-- Links -->
        <div class="w-full flex flex-col gap-4">
            @foreach($links as $link)
                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" 
                   class="w-full bg-[#f39c12] rounded-full py-4 px-5 text-white font-semibold text-center 
                          transition-all duration-200 hover:bg-[#e67e22] hover:scale-[1.01] active:scale-[0.99]">
                    {{ $link->title }}
                </a>
            @endforeach
        </div>
    </div>
</body>
</html> 