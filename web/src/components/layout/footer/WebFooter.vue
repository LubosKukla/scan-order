<template>
  <footer class="bg-deep text-white border-t border-white/10">
    <div class="mx-auto max-w-75 lg:max-w-6xl px-4 py-10 grid gap-8 lg:grid-cols-[2fr_repeat(3,1fr)] items-start">
      <div class="flex flex-col justify-start items-start gap-4">
        <img src="@/assets/img/logo-scan-order.svg" alt="EasyOrder" class="max-w-2xl h-auto" />
        <p>
          Skenuj, objednávaj a plať
          <span class="text-primary capitalize">moderne</span>
          <br />
          a
          <span class="text-primary capitalize">rýchlo.</span>
        </p>
      </div>

      <div>
        <p class="text-primary font-semibold mb-2">Linky:</p>
        <ul class="space-y-1 text-sm text-white/90">
          <li>
            <router-link to="/" class="hover:text-primary transition">Demo</router-link>
          </li>
          <li>
            <router-link to="/o-nas" class="hover:text-primary transition">GDPR</router-link>
          </li>
          <li>
            <router-link to="/admin/login" class="hover:text-primary transition">Systém</router-link>
          </li>
        </ul>
      </div>

      <div>
        <p class="text-primary font-semibold mb-2">Kontakt:</p>
        <ul class="space-y-1 text-sm text-white/90">
          <li>
            <a href="mailto:info@easyorder.sk" class="hover:text-primary transition">info@easyorder.sk</a>
          </li>
          <li>
            <a href="tel:+421917471508" class="hover:text-primary transition">+421 917 471 508</a>
          </li>
          <li>
            <a href="tel:+421944109635" class="hover:text-primary transition">+421 944 109 635</a>
          </li>
        </ul>
      </div>

      <div>
        <p class="text-primary font-semibold mb-2">Firma:</p>
        <ul class="space-y-1 text-sm text-white/90">
          <li>Web K s.r.o.</li>
          <li>IČ: 57178470</li>
          <li class="mb-4">DIČ: 2122599303</li>
          <li>Podkošariská 647/17, 029 51 Lokca</li>
        </ul>
      </div>
    </div>

    <div class="border-t border-white/10">
      <div
        class="mx-auto max-w-6xl px-4 py-6 flex flex-col gap-4 text-sm text-white/80 md:flex-row md:items-center md:justify-between"
      >
        <nav class="flex flex-wrap gap-6 justify-center font-semibold uppercase tracking-wide">
          <router-link to="/o-nas" class="hover:text-primary transition">O nás</router-link>
          <router-link to="/cennik" class="hover:text-primary transition">Cenník</router-link>
          <router-link to="/kontakt" class="hover:text-primary transition">Kontakt</router-link>
        </nav>
        <div class="flex flex-wrap justify-center items-center gap-4">
          <p class="text-center">
            © {{ new Date().getFullYear() }} Scan&Order - Web by
            <a href="https://luboskukla.sk" target="_blank" rel="noopener" class="text-primary hover:underline">
              Web K
            </a>
            &
            <a href="https://jandrugda.site" target="_blank" rel="noopener" class="text-primary hover:underline">
              Jan D.
            </a>
          </p>
          <div class="flex items-center gap-3 text-white">
            <a
              href="https://instagram.com"
              target="_blank"
              rel="noopener"
              class="inline-flex h-8 w-8 items-center justify-center rounded-full hover:bg-primary transition transform hover:scale-110"
              aria-label="Instagram"
            >
              <font-awesome-icon class="text-white" :icon="instagramIcon" />
            </a>
            <a
              href="mailto:info@easyorder.sk"
              class="inline-flex h-8 w-8 items-center justify-center rounded-full hover:bg-primary transition transform hover:scale-110"
              aria-label="E-mail"
            >
              <font-awesome-icon class="text-white" :icon="envelopeIcon" />
            </a>
            <a
              href="tel:+421917471508"
              class="inline-flex h-8 w-8 items-center justify-center rounded-full hover:bg-primary transition transform hover:scale-110"
              aria-label="Telefon"
            >
              <font-awesome-icon class="text-white" :icon="phoneIcon" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faInstagram } from '@fortawesome/free-brands-svg-icons';
import { faEnvelope, faPhone } from '@fortawesome/free-solid-svg-icons';

export default {
  name: 'WebFooter',
  components: { FontAwesomeIcon },
  data() {
    return {
      instagramIcon: faInstagram,
      envelopeIcon: faEnvelope,
      phoneIcon: faPhone,
    };
  },
};
</script>
