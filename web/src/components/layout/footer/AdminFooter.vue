<template>
  <footer class="w-full">
    <div class="bg-white text-center border-t border-black/5 shadow-[0_-1px_6px_rgba(0,0,0,0.08)]">
      <div
        class="mx-auto max-w-6xl px-4 py-3 text-xs font-small text-deep/70 flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2"
      >
        <span>&copy; Scan&Order 2025</span>
        <span class="hidden sm:inline text-deep/30">•</span>
        <a href="https://luboskukla.sk" target="_blank" rel="noopener noreferrer" class="transition-colors">
          Web by
          <span class="hover:underline hover:underline-offset-4 hover:text-primary">Web K</span>
        </a>
        &
        <a href="https://jandrugda.site" target="_blank" rel="noopener noreferrer" class="transition-colors">
          <span class="hover:underline hover:underline-offset-4 hover:text-primary">Ján D.</span>
        </a>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AdminFooter',
};
</script>
