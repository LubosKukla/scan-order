<template>
  <section class="page space-y-6">
    <AdminPageHeader title="Nastavenia" subtitle="Spravujte profil reštaurácie a preferencie.">
      <template #actions>
        <BaseButton variant="secondary" icon="refresh" @click="resetProfile">Resetovať</BaseButton>
        <BaseButton icon="confirm" @click="saveAll">Uložiť zmeny</BaseButton>
      </template>
    </AdminPageHeader>

    <div class="space-y-6">
      <BaseCard class="space-y-6">
        <div class="flex items-start gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
            <font-awesome-icon :icon="faStore" class="h-5 w-5" />
          </div>
          <div>
            <p class="text-base font-semibold text-deep">Profil reštaurácie</p>
            <p class="text-sm text-deep/70">Základné informácie o vašej reštaurácii</p>
          </div>
        </div>

        <div class="grid gap-3 md:grid-cols-2">
          <BaseInput v-model="profileForm.name" label="Názov reštaurácie" placeholder="Reštaurácia" required />
          <BaseInput
            v-model="profileForm.owner"
            label="Meno majiteľa / zodpovednej osoby"
            placeholder="Meno a priezvisko"
            required
          />
          <BaseInput v-model="profileForm.email" type="email" label="Email" placeholder="info@restauracia.sk" />
          <BaseInput v-model="profileForm.phone" label="Telefónne číslo" placeholder="+421 900 000 000" />
          <div class="space-y-1">
            <BaseSelect v-model="profileForm.restaurantType" label="Typ reštaurácie" :options="restaurantTypeOptions" />
            <BaseInput
              v-if="profileForm.restaurantType === 'other'"
              v-model="profileForm.otherRestaurantType"
              label="Môj typ"
              placeholder="Napíšte vlastný typ reštaurácie"
            />
          </div>
          <div class="space-y-1">
            <BaseSelect v-model="profileForm.cuisine" label="Typ kuchyne" :options="cuisineOptions" />
            <BaseInput
              v-if="profileForm.cuisine === 'other'"
              v-model="profileForm.otherCuisine"
              label="Môj typ"
              placeholder="Napíšte vlastný typ kuchyne"
            />
          </div>
        </div>

        <BaseTextarea
          v-model="profileForm.description"
          label="Krátky popis reštaurácie"
          placeholder="Pár viet o ponuke a atmosfére..."
          rows="3"
        />

        <div class="grid gap-3 md:grid-cols-2">
          <div class="grid gap-3 md:col-span-2 md:grid-cols-2">
            <BaseInput v-model="profileForm.address.street" label="Ulica a číslo" placeholder="Hlavná 123" />
            <BaseInput v-model="profileForm.address.zip" label="PSČ" placeholder="811 01" />
            <BaseInput v-model="profileForm.address.city" label="Mesto" placeholder="Bratislava" />
            <BaseInput
              v-model="profileForm.seats"
              type="number"
              label="Počet stolov (orientačne)"
              min="0"
              placeholder="20"
            />
          </div>
          <div class="space-y-3 rounded-xl bg-ink/30 p-4 md:grid-cols-2 md:col-span-2">
            <p class="text-sm font-semibold text-deep">Logo reštaurácie (voliteľné)</p>
            <div class="flex flex-wrap items-start justify-between gap-4 flex-col md:flex-row">
              <BaseUpload
                class="w-full md:w-[50%]"
                v-model="profileForm.logo"
                placeholder="Nahrať logo"
                accept="image/*"
              />
              <div
                v-if="logoPreviewUrl"
                class="h-auto w-full md:w-[45%] overflow-hidden rounded-lg border border-ink/30 bg-white"
              >
                <img :src="logoPreviewUrl" alt="Náhľad loga" class="h-full w-full object-cover" />
              </div>
              <div
                v-else
                class="flex h-16 w-16 items-center justify-center rounded-xl border border-dashed border-ink/30 text-xs text-deep/40"
              >
                Náhľad
              </div>
            </div>
            <p class="text-xs text-deep/60">Odporúčaná veľkosť: 500x500px</p>
          </div>
        </div>

        <div class="space-y-4 rounded-xl border border-ink/40 bg-ink/10 p-4">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="space-y-1">
              <p class="text-base font-semibold text-deep">Firemné údaje</p>
              <p class="text-sm text-deep/70">Vyplňte údaje pre firmu, ak fakturujete na firmu</p>
            </div>
            <BaseCheckbox v-model="profileForm.billToCompany">
              <span class="text-primary/80 font-bold">Fakturovať</span>
              na firmu
            </BaseCheckbox>
          </div>
          <div class="grid gap-3 md:grid-cols-2" v-if="profileForm.billToCompany">
            <BaseInput
              v-model="profileForm.company.name"
              label="Názov firmy"
              placeholder="Reštaurácia U Jozefa s.r.o."
              :disabled="!profileForm.billToCompany"
            />
            <BaseInput
              v-model="profileForm.company.ico"
              label="IČO"
              placeholder="12345678"
              :disabled="!profileForm.billToCompany"
            />
            <BaseInput
              v-model="profileForm.company.dic"
              label="DIČ"
              placeholder="2012345678"
              :disabled="!profileForm.billToCompany"
            />
            <BaseInput
              v-model="profileForm.company.icDph"
              label="IČ DPH (SK)"
              placeholder="SK2012345678"
              :disabled="!profileForm.billToCompany"
            />
          </div>
        </div>

        <div class="space-y-3 rounded-xl border border-ink/40 bg-ink/10 p-4">
          <p class="text-base font-semibold text-deep">Fakturačné údaje</p>
          <div class="grid gap-3 md:grid-cols-2">
            <BaseInput v-model="profileForm.billing.iban" label="Iban" placeholder="SK 01110 1565 1561 4894 4865" />
            <BaseInput v-model="profileForm.billing.street" label="Ulica a číslo" placeholder="Hlavná 123" />
            <BaseInput v-model="profileForm.billing.city" label="Mesto" placeholder="Bratislava" />
            <BaseInput v-model="profileForm.billing.zip" label="PSČ" placeholder="811 01" />
            <BaseInput v-model="profileForm.billing.country" label="Krajina" placeholder="Slovensko" />
            <BaseInput
              v-model="profileForm.billing.email"
              type="email"
              label="Fakturačný email"
              placeholder="billing@restauracia.sk"
            />
          </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
          <BaseButton variant="secondary" @click="changePassword">Zmeniť heslo</BaseButton>
          <BaseButton @click="saveProfile">Uložiť profil</BaseButton>
        </div>
      </BaseCard>

      <BaseCard class="space-y-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
            <font-awesome-icon :icon="faClock" class="h-5 w-5" />
          </div>
          <div class="flex-1">
            <p class="text-base font-semibold text-deep">Otváracie hodiny</p>
            <p class="text-sm text-deep/70">Nastavte prevádzkové hodiny reštaurácie</p>
          </div>
          <BaseButton variant="secondary" class="w-full md:w-auto" @click="copyFirstDayToAll">
            Kopírovať na všetky dni
          </BaseButton>
        </div>

        <div class="space-y-2">
          <div
            v-for="day in openingHours"
            :key="day.key"
            class="grid items-center gap-3 md:grid-cols-[140px_1fr_1fr_auto]"
          >
            <p class="text-sm font-semibold text-deep">{{ day.label }}</p>
            <BaseInput v-model="day.from" type="time" label="" :disabled="!day.open" />
            <BaseInput v-model="day.to" type="time" label="" :disabled="!day.open" />
            <div class="flex justify-end">
              <BaseToggle v-model="day.open" :label="day.open ? 'Otvorené' : 'Zatvorené'" />
            </div>
          </div>
        </div>

        <div class="flex justify-end">
          <BaseButton @click="saveHours">Uložiť hodiny</BaseButton>
        </div>
      </BaseCard>

      <BaseCard class="space-y-4">
        <div class="flex items-start gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
            <font-awesome-icon :icon="faLanguage" class="h-5 w-5" />
          </div>
          <div>
            <p class="text-base font-semibold text-deep">Jazyk rozhrania</p>
            <p class="text-sm text-deep/70">Zvoľte preferovaný jazyk aplikácie</p>
          </div>
        </div>
        <div class="sm:w-80">
          <BaseSelect v-model="language" label="Jazyk" :options="languageOptions" />
        </div>
        <div class="flex justify-end">
          <BaseButton @click="saveLanguage">Uložiť jazyk</BaseButton>
        </div>
      </BaseCard>

      <BaseCard class="space-y-4 border-danger/40 bg-danger/5">
        <div class="flex items-start gap-3">
          <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-danger/10 text-danger">
            <font-awesome-icon :icon="faTriangleExclamation" class="h-5 w-5" />
          </div>
          <div>
            <p class="text-base font-semibold text-deep">Správa účtu</p>
            <p class="text-sm text-deep/70">Deaktivácia alebo odstránenie účtu</p>
          </div>
        </div>

        <div class="space-y-2 rounded-xl border border-amber-200 bg-amber-50 p-4">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <p class="text-sm font-semibold text-deep">Dočasne zavrieť reštauráciu</p>
              <p class="text-xs text-deep/70">Zákazníci nebudú môcť vytvárať objednávky.</p>
            </div>
            <BaseToggle
              v-model="temporaryClosure"
              :label="temporaryClosure ? 'Dočasne zatvorené' : 'Otvorené'"
              @change="handleTemporaryToggle"
            />
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <BaseButton variant="secondary" @click="deactivateModalVisible = true">Deaktivovať účet</BaseButton>
          <BaseButton class="bg-danger text-white hover:bg-danger/90" @click="deleteConfirmVisible = true">
            Vymazať všetky dáta
          </BaseButton>
        </div>
        <p class="text-xs font-semibold text-danger">Vymazanie dát je trvalé a nezvratné.</p>
      </BaseCard>
    </div>
  </section>

  <BaseModal v-model="pauseModalVisible" title="Pozastaviť reštauráciu" :show-close="false">
    <p class="text-deep">
      Prevádzku označíte ako dočasne zatvorenú. Objednávky nebudú dostupné, kým stav znova nezmeníte.
    </p>
    <template #footer>
      <BaseButton variant="secondary" @click="cancelPause">Zrušiť</BaseButton>
      <BaseButton @click="confirmPause">Pozastaviť</BaseButton>
    </template>
  </BaseModal>

  <BaseModal v-model="deactivateModalVisible" title="Deaktivovať účet">
    <p class="text-deep">
      Deaktiváciou účtu pozastavíte prístup pre všetkých členov tímu. Údaje zostanú zachované, kým účet znova
      neaktivujete.
    </p>
    <template #footer>
      <BaseButton variant="secondary" @click="deactivateModalVisible = false">Zrušiť</BaseButton>
      <BaseButton @click="confirmDeactivate">Deaktivovať</BaseButton>
    </template>
  </BaseModal>

  <BaseModal v-model="deleteConfirmVisible" title="Vymazať všetky dáta?">
    <p class="text-deep">
      Táto akcia odstráni všetky údaje reštaurácie. Pred pokračovaním budete musieť potvrdiť názov reštaurácie a heslo.
    </p>
    <template #footer>
      <BaseButton variant="secondary" @click="deleteConfirmVisible = false">Zrušiť</BaseButton>
      <BaseButton class="bg-danger text-white hover:bg-danger/90" @click="openDeleteVerify">Pokračovať</BaseButton>
    </template>
  </BaseModal>

  <BaseModal v-model="deleteVerifyVisible" title="Potvrďte odstránenie">
    <div class="space-y-3">
      <p class="text-sm text-deep/70">Pre potvrdenie napíšte názov reštaurácie a heslo vlastníka.</p>
      <BaseInput
        v-model="deleteForm.restaurantName"
        :placeholder="profileForm.name"
        label="Názov reštaurácie"
        :error="deleteErrors.restaurantName"
      />
      <BaseInput
        v-model="deleteForm.password"
        type="password"
        label="Heslo"
        placeholder="********"
        :error="deleteErrors.password"
      />
    </div>
    <template #footer>
      <BaseButton variant="secondary" @click="cancelDelete">Zrušiť</BaseButton>
      <BaseButton class="bg-danger text-white hover:bg-danger/90" @click="finalizeDelete">Vymazať dáta</BaseButton>
    </template>
  </BaseModal>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
  faClock,
  faCreditCard,
  faLanguage,
  faStore,
  faTriangleExclamation,
  faWallet,
} from '@fortawesome/free-solid-svg-icons';
import AdminPageHeader from '@/components/layout/funkcionality/AdminPageHeader.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseInput from '@/components/global/inputs/BaseInput.vue';
import BaseCheckbox from '@/components/global/inputs/BaseCheckbox.vue';
import BaseSelect from '@/components/global/inputs/BaseSelect.vue';
import BaseTextarea from '@/components/global/inputs/BaseTextarea.vue';
import BaseUpload from '@/components/global/inputs/BaseUpload.vue';
import BaseToggle from '@/components/global/inputs/BaseToggle.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';
import { useSnackbar } from '@/composables/useSnackbar';

export default {
  name: 'AdminNastaveniaView',
  components: {
    AdminPageHeader,
    BaseButton,
    BaseCard,
    BaseCheckbox,
    BaseInput,
    BaseSelect,
    BaseTextarea,
    BaseUpload,
    BaseToggle,
    BaseModal,
    FontAwesomeIcon,
  },
  data() {
    return {
      faStore,
      faWallet,
      faClock,
      faLanguage,
      faTriangleExclamation,
      faCreditCard,
      snackbar: useSnackbar(),
      logoPreviewUrl: '',
      profileForm: {
        name: 'Reštaurácia U Jozefa',
        owner: 'Jozef Novák',
        email: 'info@jozefa.sk',
        phone: '+421 900 000 000',
        restaurantType: 'restaurant',
        cuisine: 'slovak',
        otherRestaurantType: '',
        otherCuisine: '',
        billToCompany: false,
        company: {
          name: 'Reštaurácia U Jozefa s.r.o.',
          ico: '12345678',
          dic: '2012345678',
          icDph: 'SK2012345678',
        },
        billing: {
          iban: 'SK 9910 1051 4784 5468 1561',
          street: 'Hlavná 123',
          city: 'Bratislava',
          zip: '811 01',
          country: 'Slovensko',
          email: 'billing@jozefa.sk',
        },
        description:
          'Tradičná slovenská reštaurácia s moderným prístupom. Ponúkame kvalitné jedlá z lokálnych surovín.',
        address: {
          street: 'Hlavná 123',
          city: 'Bratislava',
          zip: '811 01',
        },
        seats: 20,
        logo: null,
      },
      restaurantTypeOptions: [
        { label: 'Reštaurácia', value: 'restaurant' },
        { label: 'Kaviareň', value: 'cafe' },
        { label: 'Catering', value: 'catering' },
        { label: 'Food truck', value: 'foodtruck' },
        { label: 'Ostatné', value: 'other' },
      ],
      cuisineOptions: [
        { label: 'Slovenská', value: 'slovak' },
        { label: 'Talianska', value: 'italian' },
        { label: 'Ázijská', value: 'asian' },
        { label: 'Medzinárodná', value: 'international' },
        { label: 'Ostatné', value: 'other' },
      ],
      planInfo: {
        name: 'Platba - Najlepšia voľba',
        price: '47,97 €',
        nextBilling: '3. decembra 2025',
      },
      paymentMethod: {
        provider: 'Stripe',
        description: 'Bezpečná platobná brána pre online platby',
      },
      openingHours: [
        { key: 'mon', label: 'Pondelok', from: '10:00', to: '22:00', open: true },
        { key: 'tue', label: 'Utorok', from: '10:00', to: '22:00', open: true },
        { key: 'wed', label: 'Streda', from: '10:00', to: '22:00', open: true },
        { key: 'thu', label: 'Štvrtok', from: '10:00', to: '22:00', open: true },
        { key: 'fri', label: 'Piatok', from: '10:00', to: '23:00', open: true },
        { key: 'sat', label: 'Sobota', from: '10:00', to: '23:00', open: true },
        { key: 'sun', label: 'Nedeľa', from: '11:00', to: '21:00', open: true },
      ],
      language: 'sk',
      languageOptions: [
        { label: 'Slovenčina', value: 'sk' },
        { label: 'English', value: 'en' },
        { label: 'Deutsch', value: 'de' },
      ],
      temporaryClosure: false,
      pauseModalVisible: false,
      deactivateModalVisible: false,
      deleteConfirmVisible: false,
      deleteVerifyVisible: false,
      deleteForm: {
        restaurantName: '',
        password: '',
      },
      deleteErrors: {
        restaurantName: '',
        password: '',
      },
    };
  },
  watch: {
    'profileForm.logo'(newVal) {
      if (this.logoPreviewUrl && this.logoPreviewUrl.startsWith('blob:')) {
        URL.revokeObjectURL(this.logoPreviewUrl);
      }
      if (!newVal) {
        this.logoPreviewUrl = '';
        return;
      }
      if (typeof newVal === 'string') {
        this.logoPreviewUrl = newVal;
        return;
      }
      this.logoPreviewUrl = URL.createObjectURL(newVal);
    },
  },
  beforeUnmount() {
    if (this.logoPreviewUrl && this.logoPreviewUrl.startsWith('blob:')) {
      URL.revokeObjectURL(this.logoPreviewUrl);
    }
  },
  methods: {
    saveAll() {
      this.snackbar.notify({ message: 'Zmeny boli uložené.', variant: 'success' });
    },
    resetProfile() {
      this.clearLogoPreview();
      this.profileForm = {
        name: 'Reštaurácia U lubosa a janka',
        owner: 'Jozef Novák',
        email: 'info@lubosajan.sk',
        phone: '+421 900 000 000',
        restaurantType: 'restaurant',
        cuisine: 'slovak',
        otherRestaurantType: '',
        otherCuisine: '',
        billToCompany: false,
        company: {
          name: 'Reštaurácia U lubosa a janka s.r.o.',
          ico: '12345678',
          dic: '2012345678',
          icDph: 'SK2012345678',
        },
        billing: {
          iban: 'SK 9910 1051 4784 5468 1561',
          street: 'Hlavná 123',
          city: 'Bratislava',
          zip: '811 01',
          country: 'Slovensko',
          email: 'billing@lubosajan.sk',
        },
        description:
          'Tradičná slovenská reštaurácia s moderným prístupom. Ponúkame kvalitné jedlá z lokálnych surovín.',
        address: { street: 'Hlavná 123', city: 'Bratislava', zip: '811 01' },
        seats: 20,
        logo: null,
      };
      this.snackbar.notify({ message: 'Formulár bol resetovaný.', variant: 'info' });
    },
    clearLogoPreview() {
      if (this.logoPreviewUrl && this.logoPreviewUrl.startsWith('blob:')) {
        URL.revokeObjectURL(this.logoPreviewUrl);
      }
      this.logoPreviewUrl = '';
    },
    saveProfile() {
      this.snackbar.notify({ message: 'Profil reštaurácie bol uložený.', variant: 'success' });
    },
    changePassword() {
      this.snackbar.notify({ message: 'Odoslaný odkaz na zmenu hesla.', variant: 'info' });
    },
    changePlan() {
      this.snackbar.notify({ message: 'Otvorte výber iného plánu.', variant: 'info' });
    },
    editPayment() {
      this.snackbar.notify({ message: 'Úprava platobnej metódy.', variant: 'info' });
    },
    showHistory() {
      this.snackbar.notify({ message: 'Zobrazenie histórie platieb.', variant: 'info' });
    },
    copyFirstDayToAll() {
      if (!this.openingHours.length) return;
      const source = this.openingHours[0];
      this.openingHours = this.openingHours.map((day) => ({
        ...day,
        from: source.from,
        to: source.to,
        open: source.open,
      }));
      this.snackbar.notify({ message: 'Hodiny skopírované na všetky dni.', variant: 'success' });
    },
    saveHours() {
      this.snackbar.notify({ message: 'Otváracie hodiny boli uložené.', variant: 'success' });
    },
    saveLanguage() {
      const option = this.languageOptions.find((opt) => opt.value === this.language);
      this.snackbar.notify({
        message: `Jazyk nastavený na ${option?.label || 'vybraný jazyk'}.`,
        variant: 'success',
      });
    },
    handleTemporaryToggle(value) {
      if (value) {
        this.temporaryClosure = false;
        this.pauseModalVisible = true;
      }
    },
    cancelPause() {
      this.pauseModalVisible = false;
      this.temporaryClosure = false;
    },
    confirmPause() {
      this.pauseModalVisible = false;
      this.temporaryClosure = true;
      this.snackbar.notify({ message: 'Prevádzka je dočasne zatvorená.', variant: 'info' });
    },
    confirmDeactivate() {
      this.deactivateModalVisible = false;
      this.snackbar.notify({ message: 'Účet bol deaktivovaný.', variant: 'warning' });
    },
    openDeleteVerify() {
      this.deleteConfirmVisible = false;
      this.deleteVerifyVisible = true;
      this.deleteForm = { restaurantName: '', password: '' };
      this.deleteErrors = { restaurantName: '', password: '' };
    },
    cancelDelete() {
      this.deleteVerifyVisible = false;
      this.deleteErrors = { restaurantName: '', password: '' };
    },
    finalizeDelete() {
      this.deleteErrors = { restaurantName: '', password: '' };
      if (this.deleteForm.restaurantName.trim() !== this.profileForm.name.trim()) {
        this.deleteErrors.restaurantName = 'Názov reštaurácie sa nezhoduje.';
      }
      if (!this.deleteForm.password.trim()) {
        this.deleteErrors.password = 'Zadajte heslo.';
      }
      if (this.deleteErrors.restaurantName || this.deleteErrors.password) return;
      this.deleteVerifyVisible = false;
      this.snackbar.notify({ message: 'Všetky dáta boli odstránené.', variant: 'danger' });
    },
  },
};
</script>
