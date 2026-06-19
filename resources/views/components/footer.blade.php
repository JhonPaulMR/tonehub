<footer class="w-full py-8 px-gutter flex flex-col md:flex-row justify-between items-center mt-auto bg-background border-t border-outline-variant {{ auth()->check() ? 'lg:pl-72' : '' }} z-30 relative">
    <div class="mb-4 md:mb-0">
        <span class="text-label-bold font-label-bold text-on-surface">ToneHUB</span>
        <p class="text-label-sm font-label-sm text-on-surface-variant mt-1">© {{ date('Y') }} ToneHUB Audio. All rights reserved.</p>
    </div>
    <div class="flex gap-6">
        <a href="#" class="text-label-sm font-label-sm text-on-secondary-fixed-variant hover:text-primary transition-colors">Terms</a>
        <a href="#" class="text-label-sm font-label-sm text-on-secondary-fixed-variant hover:text-primary transition-colors">Privacy</a>
    </div>
</footer>
