<section class="relative hidden flex-col justify-between overflow-hidden bg-gradient-to-br from-[#064e3b] via-[#047857] to-[#0f766e] p-10 text-white lg:flex">
    <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10 blur-2xl"></div>
    <div class="absolute -bottom-28 -left-20 h-80 w-80 rounded-full bg-emerald-300/20 blur-3xl"></div>

    <a href="{{ route('home') }}" class="relative flex w-fit items-center gap-3">
        <img src="{{ asset('images/Logo.png') }}" alt="RentCampy" class="h-12 w-12 rounded-xl object-cover">
        <span class="text-xl font-bold tracking-wide">RentCampy</span>
    </a>

    <div class="relative">
        <p class="mb-3 text-sm font-semibold uppercase tracking-[0.2em] text-emerald-200">{{ $eyebrow }}</p>
        <h1 class="max-w-md text-4xl font-bold leading-tight">{{ $heading }}</h1>
        <p class="mt-4 max-w-md leading-7 text-emerald-50/85">{{ $text }}</p>
        <img src="{{ asset('images/welcome-image.png') }}" alt="Perlengkapan camping RentCampy" class="mx-auto mt-8 h-56 w-full object-contain drop-shadow-2xl">
    </div>

    <p class="relative text-sm text-emerald-100/70">Perlengkapan tepat, petualangan lebih hebat.</p>
</section>
