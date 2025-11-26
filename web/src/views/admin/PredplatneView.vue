<template>
  <section class="page space-y-6">
    <AdminPageHeader title="Predplatné & Fakturácia" subtitle="Spravujte balíky, platby a fakturačné údaje." />

    <BaseCard class="space-y-4 border-primary/30 bg-primary/5!">
      <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="flex items-start gap-3">
          <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
            <font-awesome-icon :icon="trialIcon" class="h-6 w-6" />
          </div>
          <div class="space-y-1">
            <p class="text-base font-semibold text-deep">Využívaš skúšobné obdobie (3 mesiace zdarma)</p>
            <p class="text-sm text-deep/70">
              Zostáva ti ešte
              <span class="font-semibold text-primary">{{ trial.daysLeft }}</span>
              dní z {{ trial.totalDays }} dní skúšobného obdobia.
            </p>
          </div>
        </div>

        <BaseButton class="w-full md:w-auto" variant="secondary" @click="scrollToSection('pricingRef')">
          Zmeniť budúci balík
        </BaseButton>
      </div>

      <div class="space-y-2">
        <div class="h-2 w-full overflow-hidden rounded-full bg-deep/10 mb-4">
          <div
            class="h-full rounded-full bg-primary transition-all"
            :style="{ width: `${(trial.daysLeft / trial.totalDays) * 100}%` }"
          ></div>
        </div>

        <div class="flex gap-2">
          <font-awesome-icon :icon="delivery" class="h-5 w-5 text-deep/50" />
          <p class="text-xs text-deep/60">Po skončení skúšobného obdobia sa automaticky aktivuje tvoj zvolený balík.</p>
        </div>
      </div>
    </BaseCard>

    <BaseCard class="space-y-4">
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="space-y-1">
          <p class="text-sm font-semibold text-deep/70">Aktuálny plán</p>
          <p class="text-lg font-bold text-deep">{{ currentPlan.name }}</p>
          <div class="flex items-center gap-2 text-sm text-deep/80">
            <div class="rounded-lg bg-primary/70 px-2">
              <span class="text-xs font-semibold text-white">Aktívne</span>
            </div>
            <span class="text-deep/60">Obnovuje sa automaticky</span>
          </div>
        </div>
        <div class="flex flex-col gap-2 md:flex-row md:items-center">
          <BaseButton variant="secondary" @click="scrollToSection('pricingRef')">Zmeniť balík</BaseButton>
          <BaseButton variant="secondary" @click="scrollToSection('historyRef')">História faktúr</BaseButton>
          <BaseButton variant="secondary" class="text-danger! align-middle" @click="cancelPlan()">
            Zrušiť predplatné
          </BaseButton>
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-1">
          <p class="text-3xl font-extrabold text-deep">
            {{ displayPrice(currentPlan) }}
            <span class="text-sm font-semibold text-deep/70">/ {{ isYearly ? 'mesiac (ročné)' : 'mesiac' }}</span>
          </p>
          <p class="text-sm text-deep/70">
            Zostávajúce dni:
            <span class="font-semibold text-deep">{{ currentPlan.daysLeft }}</span>
            z
            {{ currentPlan.billingCycleDays }}
          </p>
          <div class="h-2 w-full overflow-hidden rounded-full bg-deep/10">
            <div
              class="h-full rounded-full bg-primary"
              :style="{ width: `${(currentPlan.daysLeft / currentPlan.billingCycleDays) * 100}%` }"
            ></div>
          </div>
        </div>
        <div class="space-y-2 rounded-xl border border-ink/20 bg-ink/10 p-3">
          <div class="grid grid-cols-2 gap-3 text-sm text-deep/70">
            <div>
              <p class="font-semibold text-deep/80">Dátum začiatku</p>
              <p>{{ currentPlan.startDate }}</p>
            </div>
            <div>
              <p class="font-semibold text-deep/80">Dátum konca</p>
              <p>{{ currentPlan.endDate }}</p>
            </div>
          </div>
          <p class="text-xs mt-4 text-deep/60">
            Predplatné sa automaticky obnoví
            <span class="font-semibold text-deep">{{ currentPlan.renewDate }}</span>
            .
          </p>
        </div>
      </div>
    </BaseCard>

    <div ref="pricingRef" class="space-y-3">
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <p class="text-lg font-semibold text-deep">Cenníky</p>
          <p class="text-sm text-deep/70">Vyberte si balík, ktorý najlepšie vyhovuje vašim potrebám.</p>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-sm text-deep/70">Fakturačný cyklus</span>
          <BaseToggle v-model="isYearly" :label="isYearly ? 'Ročne (-20%)' : 'Mesačne'" />
        </div>
      </div>

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <BaseCard
          v-for="plan in displayPlans"
          :key="plan.id"
          class="relative space-y-4"
          :class="{
            'border-primary/50 shadow-lg': plan.id === currentPlan.id,
            'bg-primary/5': plan.id === currentPlan.id,
          }"
        >
          <div class="flex items-center justify-between gap-2">
            <div>
              <p class="text-sm font-semibold text-deep/70">{{ plan.label }}</p>
              <p class="text-base font-bold text-deep">{{ plan.name }}</p>
            </div>
            <span
              v-if="plan.tag || plan.id === currentPlan.id"
              class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary"
            >
              {{ plan.id === currentPlan.id ? 'Aktívny' : plan.tag }}
            </span>
          </div>
          <div class="space-y-1">
            <p class="text-2xl font-extrabold text-deep">
              {{ formatPrice(plan.displayMonthly) }}
              <span class="text-sm font-semibold text-deep/60">/ mesiac</span>
            </p>
            <p v-if="isYearly" class="text-xs text-deep/60">
              {{ formatPrice(plan.yearlyTotal) }} / rok &middot; účtované ročne
            </p>
          </div>
          <ul class="space-y-1 text-sm text-deep/80">
            <li v-for="(feature, idx) in plan.features" :key="idx" class="flex items-start gap-2">
              <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
              <span>{{ feature }}</span>
            </li>
          </ul>
          <BaseButton
            class="w-full justify-center"
            :variant="plan.id === currentPlan.id ? 'secondary' : 'primary'"
            :disabled="plan.id === currentPlan.id"
          >
            {{ plan.id === currentPlan.id ? 'Aktívny balík' : 'Prejsť na tento balík' }}
          </BaseButton>
        </BaseCard>
      </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2" ref="billingRef">
      <BaseCard class="space-y-3">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-base font-semibold text-deep">Platobná metóda</p>
            <p class="text-sm text-deep/70">Spravujte uloženú platobnú kartu</p>
          </div>
          <span class="rounded-full bg-deep/15 px-3 py-1 text-xs font-semibold text-deep">Primárna</span>
        </div>
        <div class="flex items-center gap-3 rounded-xl border border-ink/20 bg-ink/10 p-3">
          <div class="rounded-lg bg-primary px-3 py-2 text-sm font-bold text-white">VISA</div>
          <div class="flex-1 text-sm text-deep/80">
            <p class="font-semibold">•••• •••• •••• {{ paymentMethod.last4 }}</p>
            <p>Platí do {{ paymentMethod.expiry }}</p>
          </div>
        </div>
        <BaseButton variant="secondary" class="w-full justify-center md:w-auto" @click="showCardModal = true">
          Zmeniť kartu
        </BaseButton>
      </BaseCard>

      <BaseCard class="space-y-3">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-base font-semibold text-deep">Fakturačné údaje</p>
            <p class="text-sm text-deep/70">Spravujte fakturačné informácie</p>
          </div>
        </div>
        <div class="space-y-1 text-sm text-deep/80">
          <p>
            <span class="font-semibold text-deep/70">Fakturujem ako:</span>
            {{ billingInfo.isCompany ? 'Firma' : 'Súkromná osoba' }}
          </p>
          <p v-if="billingInfo.isCompany">
            <span class="font-semibold text-deep/70">Názov firmy:</span>
            {{ billingInfo.companyName }}
          </p>
          <p v-else>
            <span class="font-semibold text-deep/70">Meno a priezvisko:</span>
            {{ billingInfo.fullName }}
          </p>

          <p v-if="billingInfo.isCompany">
            <span class="font-semibold text-deep/70">IČO:</span>
            {{ billingInfo.ico }}
          </p>
          <p v-if="billingInfo.isCompany">
            <span class="font-semibold text-deep/70">DIČ:</span>
            {{ billingInfo.dic }}
          </p>
          <p v-if="billingInfo.isCompany">
            <span class="font-semibold text-deep/70">IČ DPH:</span>
            {{ billingInfo.icDph }}
          </p>

          <p>
            <span class="font-semibold text-deep/70">Adresa:</span>
            {{ billingInfo.street }}, {{ billingInfo.zip }} {{ billingInfo.city }}
          </p>
          <p v-if="billingInfo.country">
            <span class="font-semibold text-deep/70">Štát:</span>
            {{ billingInfo.country }}
          </p>
          <p>
            <span class="font-semibold text-deep/70">Email:</span>
            {{ billingInfo.email }}
          </p>
        </div>
        <BaseButton variant="secondary" class="w-full justify-center md:w-auto" @click="showBillingModal = true">
          Upraviť údaje
        </BaseButton>
      </BaseCard>
    </div>

    <BaseCard ref="historyRef" class="space-y-3">
      <div>
        <p class="text-base font-semibold text-deep">História faktúr</p>
        <p class="text-sm text-deep/70">Prehľad vašich faktúr a platieb</p>
      </div>
      <div class="overflow-x-auto hidden md:block">
        <table class="min-w-full text-sm text-deep">
          <thead>
            <tr class="text-left text-deep/60">
              <th class="py-2">Číslo faktúry</th>
              <th class="py-2">Dátum</th>
              <th class="py-2">Suma</th>
              <th class="py-2">Stav</th>
              <th class="py-2 text-right">Akcie</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="invoice in invoices" :key="invoice.id" class="border-t border-ink/20">
              <td class="py-3 font-semibold">{{ invoice.id }}</td>
              <td class="py-3 flex items-center gap-2 text-deep/80">
                <font-awesome-icon :icon="faCalendar" class="text-deep/50" />
                {{ invoice.date }}
              </td>
              <td class="py-3">{{ formatPrice(invoice.amount) }}</td>
              <td class="py-3">
                <PaymentStatusBadge :status="invoice.status" />
              </td>
              <td class="py-3 text-right">
                <BaseButton
                  variant="noBackground"
                  icon="download"
                  class="!px-0 !py-1 text-primary hover:text-primary/80"
                  type="button"
                >
                  Stiahnuť
                </BaseButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="md:hidden space-y-3">
        <div
          v-for="invoice in invoices"
          :key="invoice.id"
          class="rounded-2xl border border-ink/15 bg-white px-3 py-3 shadow-2xs"
        >
          <div class="flex items-center justify-between">
            <div class="font-semibold text-deep">{{ invoice.id }}</div>
            <PaymentStatusBadge :status="invoice.status" />
          </div>
          <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-deep/80">
            <div class="flex items-center gap-2">
              <font-awesome-icon :icon="faCalendar" class="text-deep/50" />
              <span>{{ invoice.date }}</span>
            </div>
            <div class="text-right font-semibold text-deep">
              {{ formatPrice(invoice.amount) }}
            </div>
          </div>
          <div class="mt-3 flex justify-end">
            <BaseButton
              variant="noBackground"
              icon="download"
              class="px-0! py-1! text-primary hover:text-primary/80 text-sm font-medium"
              type="button"
            >
              Stiahnuť
            </BaseButton>
          </div>
        </div>
      </div>
    </BaseCard>
  </section>

  <BaseModal v-model="showCardModal" title="Zmeniť kartu">
    <div class="space-y-3">
      <BaseInput v-model="cardForm.cardholder" label="Meno na karte" placeholder="Ján Novák" />
      <BaseInput v-model="cardForm.number" label="Číslo karty" placeholder="4242 4242 4242 4242" />
      <div class="grid gap-3 md:grid-cols-2">
        <BaseInput v-model="cardForm.expiry" label="Platnosť" placeholder="MM/RR" />
        <BaseInput v-model="cardForm.cvc" label="CVC" placeholder="123" />
      </div>
    </div>
    <template #footer>
      <BaseButton variant="secondary" @click="showCardModal = false">Zrušiť</BaseButton>
      <BaseButton @click="saveCard">Uložiť kartu</BaseButton>
    </template>
  </BaseModal>

  <BaseModal v-model="showBillingModal" title="Upraviť fakturačné údaje">
    <div class="space-y-4">
      <BaseCheckbox v-model="billingForm.isCompany">
        <span class="text-primary/80 font-bold">Fakturovať</span>
        na firmu
      </BaseCheckbox>

      <BaseInput v-if="billingForm.isCompany" v-model="billingForm.companyName" label="Názov firmy" />
      <BaseInput v-else v-model="billingForm.fullName" label="Meno a priezvisko" />

      <div v-if="billingForm.isCompany" class="grid gap-3 md:grid-cols-3">
        <BaseInput v-model="billingForm.ico" label="IČO" />
        <BaseInput v-model="billingForm.dic" label="DIČ" />
        <BaseInput v-model="billingForm.icDph" label="IČ DPH" />
      </div>

      <div class="grid gap-3 md:grid-cols-2">
        <BaseInput v-model="billingForm.street" label="Ulica a číslo" />
        <BaseInput v-model="billingForm.city" label="Mesto" />
      </div>
      <div class="grid gap-3 md:grid-cols-2">
        <BaseInput v-model="billingForm.zip" label="PSČ" />
        <BaseInput v-model="billingForm.country" label="Štát" />
      </div>

      <BaseInput v-model="billingForm.email" label="Email" type="email" />
    </div>
    <template #footer>
      <BaseButton variant="secondary" @click="showBillingModal = false">Zrušiť</BaseButton>
      <BaseButton @click="saveBilling">Uložiť údaje</BaseButton>
    </template>
  </BaseModal>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCalendar } from '@fortawesome/free-regular-svg-icons';
import { faCircleExclamation, faWandMagicSparkles } from '@fortawesome/free-solid-svg-icons';
import AdminPageHeader from '@/components/layout/funkcionality/AdminPageHeader.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseInput from '@/components/global/inputs/BaseInput.vue';
import BaseCheckbox from '@/components/global/inputs/BaseCheckbox.vue';
import BaseToggle from '@/components/global/inputs/BaseToggle.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';
import PaymentStatusBadge from '@/components/global/payments/PaymentStatusBadge.vue';
import { useSnackbar } from '@/composables/useSnackbar';

export default {
  name: 'AdminPredplatneView',
  components: {
    AdminPageHeader,
    BaseButton,
    BaseCard,
    BaseCheckbox,
    BaseInput,
    BaseToggle,
    BaseModal,
    PaymentStatusBadge,
    FontAwesomeIcon,
  },
  data() {
    return {
      faCalendar,
      delivery: faCircleExclamation,
      trialIcon: faWandMagicSparkles,
      snackbar: useSnackbar(),
      isYearly: false,
      showCardModal: false,
      showBillingModal: false,
      trial: {
        daysLeft: 52,
        totalDays: 90,
      },
      currentPlan: {
        id: 'payment',
        name: 'Platba - Najlepšia voľba',
        monthlyPrice: 47.97,
        billingCycleDays: 30,
        daysLeft: 11,
        startDate: '15.10.2024',
        endDate: '15.11.2024',
        renewDate: '15.11.2025',
      },
      plans: [
        {
          id: 'menu',
          name: 'Menu',
          label: 'Základné',
          monthlyPrice: 8.49,
          features: ['Digitálne menu reštaurácie', 'Zákazník môže požiadať o čašníka', 'Fotky jedál podľa kategórií'],
        },
        {
          id: 'order',
          name: 'Objednávka',
          label: 'Dobrá voľba',
          monthlyPrice: 38.98,
          features: [
            'Všetky výhody z balíka Menu',
            'Objednávkový systém',
            'Napojenie na platobný systém (platba pri stole)',
            'Možnosť správy denného menu',
          ],
        },
        {
          id: 'payment',
          name: 'Platba',
          label: 'Najlepšia voľba',
          monthlyPrice: 47.97,
          tag: 'Populárne',
          features: [
            'Všetky výhody z balíka Objednávka',
            'Online platby priamo cez mobil',
            'Detailná štatistika predaja',
            'Napojenie na kuchynské systémy',
            'Správa zliav a akcií',
          ],
        },
        {
          id: 'exclusive',
          name: 'Exclusive',
          label: 'Prémiové',
          monthlyPrice: 69.96,
          features: [
            'Všetky výhody z balíka Platba',
            'Vlastný dizajn a predčíslie predaja',
            'Integrácia s externými systémami (ERP, sklady)',
          ],
        },
      ],
      paymentMethod: {
        brand: 'VISA',
        last4: '4242',
        expiry: '12/2026',
      },
      billingInfo: {
        isCompany: true,
        companyName: 'Reštaurácia U Jozefa s.r.o.',
        fullName: '',
        ico: '12345678',
        dic: '2023456789',
        icDph: 'SK2023456789',
        street: 'Hlavná 123',
        city: 'Bratislava',
        zip: '811 01',
        country: 'Slovensko',
        email: 'fakturacia@jozefa.sk',
      },
      billingForm: {
        isCompany: true,
        companyName: 'Reštaurácia U Jozefa s.r.o.',
        fullName: '',
        ico: '12345678',
        dic: '2023456789',
        icDph: 'SK2023456789',
        street: 'Hlavná 123',
        city: 'Bratislava',
        zip: '811 01',
        country: 'Slovensko',
        email: 'fakturacia@jozefa.sk',
      },

      cardForm: {
        cardholder: '',
        number: '',
        expiry: '',
        cvc: '',
      },

      invoices: [
        { id: 'INV-2024-001', date: '15.10.2024', amount: 47.97, status: 'Zaplatené' },
        { id: 'INV-2024-002', date: '15.09.2024', amount: 47.97, status: 'Zaplatené' },
        { id: 'INV-2024-003', date: '15.08.2024', amount: 47.97, status: 'Zaplatené' },
      ],
    };
  },
  computed: {
    displayPlans() {
      return this.plans.map((plan) => {
        const monthly = this.isYearly ? plan.monthlyPrice * 0.8 : plan.monthlyPrice;
        return {
          ...plan,
          displayMonthly: monthly,
          yearlyTotal: monthly * 12,
        };
      });
    },
  },
  methods: {
    formatPrice(value) {
      return `${value.toFixed(2)} €`;
    },
    displayPrice(plan) {
      const monthly = this.isYearly ? plan.monthlyPrice * 0.8 : plan.monthlyPrice;
      return this.formatPrice(monthly);
    },
    scrollToSection(refName) {
      const target = this.$refs[refName];
      const el = target?.$el ?? target; // ak je to komponent, vezmeme $el

      if (el && el.scrollIntoView) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    },

    saveCard() {
      if (this.cardForm.number) {
        this.paymentMethod.last4 = this.cardForm.number.replace(/\s+/g, '').slice(-4);
      }
      if (this.cardForm.expiry) {
        this.paymentMethod.expiry = this.cardForm.expiry;
      }

      this.showCardModal = false;
      this.snackbar.notify({ message: 'Platobná karta bola aktualizovaná.', variant: 'success' });
      this.cardForm = { cardholder: '', number: '', expiry: '', cvc: '' };
    },

    saveBilling() {
      this.billingInfo = { ...this.billingForm };
      this.showBillingModal = false;
      this.snackbar.notify({ message: 'Fakturačné údaje boli uložené.', variant: 'success' });
    },

    cancelPlan() {
      this.snackbar.notify({ message: 'Predplatné bolo zrušené', variant: 'deep' });
    },
  },
};
</script>
