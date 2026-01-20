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
          <BaseInput
            v-model="profileForm.name"
            label="Názov reštaurácie"
            placeholder="Reštaurácia"
            required
            :error="errors.name"
          />
          <BaseInput
            v-model="profileForm.owner"
            label="Meno majiteľa / zodpovednej osoby"
            placeholder="Meno a priezvisko"
            required
            :error="errors.owner"
          />
          <BaseInput
            v-model="profileForm.email"
            type="email"
            label="Email"
            placeholder="info@restauracia.sk"
            required
            :error="errors.email"
          />
          <BaseInput
            v-model="profileForm.phone"
            label="Telefónne číslo"
            placeholder="+421 900 000 000"
            required
            :error="errors.phone"
          />
          <div class="space-y-1">
            <BaseSelect
              v-model="profileForm.restaurantType"
              label="Typ reštaurácie"
              :options="restaurantTypeOptions"
              option-label-key="type"
              option-value-key="type"
              :error="errors.restaurantType"
            />
            <BaseInput
              v-if="profileForm.restaurantType === 'Ostatné'"
              v-model="profileForm.otherRestaurantType"
              label="Môj typ"
              placeholder="Napíšte vlastný typ reštaurácie"
              :error="errors.otherRestaurantType"
            />
          </div>
          <div class="space-y-1">
            <BaseSelect
              v-model="profileForm.cuisine"
              label="Typ kuchyne"
              :options="cuisineOptions"
              option-label-key="type"
              option-value-key="type"
              :error="errors.cuisine"
            />
            <BaseInput
              v-if="profileForm.cuisine === 'Ostatné'"
              v-model="profileForm.otherCuisine"
              label="Môj typ"
              placeholder="Napíšte vlastný typ kuchyne"
              :error="errors.otherCuisine"
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
            <BaseInput
              v-model="profileForm.address.street"
              label="Ulica a číslo"
              placeholder="Hlavná 123"
              :error="errors.addressStreet"
            />
            <BaseInput v-model="profileForm.address.zip" label="PSČ" placeholder="811 01" :error="errors.addressZip" />
            <BaseInput
              v-model="profileForm.address.city"
              label="Mesto"
              placeholder="Bratislava"
              :error="errors.addressCity"
            />
            <BaseInput
              v-model="profileForm.seats"
              type="number"
              label="Počet stolov (orientačne)"
              min="0"
              placeholder="20"
              :error="errors.seats"
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
              :error="errors.companyName"
            />
            <BaseInput
              v-model="profileForm.company.ico"
              label="IČO"
              placeholder="12345678"
              :disabled="!profileForm.billToCompany"
              :error="errors.companyIco"
            />
            <BaseInput
              v-model="profileForm.company.dic"
              label="DIČ"
              placeholder="2012345678"
              :disabled="!profileForm.billToCompany"
              :error="errors.companyDic"
            />
            <BaseInput
              v-model="profileForm.company.icDph"
              label="IČ DPH (SK)"
              placeholder="SK2012345678"
              :disabled="!profileForm.billToCompany"
              :error="errors.companyIcDph"
            />
          </div>
        </div>

        <div class="space-y-3 rounded-xl border border-ink/40 bg-ink/10 p-4">
          <p class="text-base font-semibold text-deep">Fakturačné údaje</p>
          <div class="grid gap-3 md:grid-cols-2">
            <BaseInput
              v-model="profileForm.billing.iban"
              label="Iban"
              placeholder="SK 01110 1565 1561 4894 4865"
              :error="errors.billingIban"
            />
            <BaseInput
              v-model="profileForm.billing.street"
              label="Ulica a číslo"
              placeholder="Hlavná 123"
              :error="errors.billingStreet"
            />
            <BaseInput
              v-model="profileForm.billing.city"
              label="Mesto"
              placeholder="Bratislava"
              :error="errors.billingCity"
            />
            <BaseInput v-model="profileForm.billing.zip" label="PSČ" placeholder="811 01" :error="errors.billingZip" />
            <BaseInput
              v-model="profileForm.billing.country"
              label="Krajina"
              placeholder="Slovensko"
              :error="errors.billingCountry"
            />
            <BaseInput
              v-model="profileForm.billing.email"
              type="email"
              label="Fakturačný email"
              placeholder="billing@restauracia.sk"
              :error="errors.billingEmail"
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
          <div class="flex w-full flex-col gap-2 md:w-auto md:flex-row">
            <BaseButton variant="secondary" class="w-full md:w-auto" @click="copyFirstDayToAll">
              Kopírovať na všetky dni
            </BaseButton>
            <BaseButton variant="danger" class="w-full md:w-auto" icon="trash" @click="deleteHours">
              Vymazať hodiny
            </BaseButton>
          </div>
        </div>

        <div class="space-y-2">
          <div
            v-for="day in open_hours"
            :key="day.day_of_week"
            class="grid items-center gap-3 md:grid-cols-[140px_1fr_1fr_auto]"
          >
            <p class="text-sm font-semibold text-deep">{{ dayLabel(day.day_of_week) }}</p>
            <BaseInput
              :modelValue="day.open_time"
              type="time"
              label=""
              :disabled="day.is_closed"
              @update:modelValue="updateHour(day.day_of_week, 'open_time', $event)"
            />
            <BaseInput
              :modelValue="day.close_time"
              type="time"
              label=""
              :disabled="day.is_closed"
              @update:modelValue="updateHour(day.day_of_week, 'close_time', $event)"
            />
            <div class="flex justify-end">
              <BaseToggle
                :modelValue="!day.is_closed"
                :label="day.is_closed ? 'Zatvorené' : 'Otvorené'"
                @update:modelValue="toggleDay(day.day_of_week, $event)"
              />
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
              :modelValue="!temporaryClosure"
              :label="temporaryClosure ? 'Dočasne zatvorené' : 'Otvorené'"
              @update:modelValue="handleTemporaryToggle"
            />
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <BaseButton variant="secondary" @click="deactivateModalVisible = true">
            {{ accountActive ? 'Deaktivovať účet' : 'Aktivovať účet' }}
          </BaseButton>
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

  <BaseModal v-model="deactivateModalVisible" :title="accountActive ? 'Deaktivovať účet' : 'Aktivovať účet'">
    <p class="text-deep">
      {{
        accountActive
          ? 'Deaktiváciou účtu pozastavíte prístup pre všetkých členov tímu. Údaje zostanú zachované, kým účet znova neaktivujete.'
          : 'Aktiváciou účtu obnovíte prístup pre všetkých členov tímu.'
      }}
    </p>
    <template #footer>
      <BaseButton variant="secondary" @click="deactivateModalVisible = false">Zrušiť</BaseButton>
      <BaseButton @click="confirmDeactivate">{{ accountActive ? 'Deaktivovať' : 'Aktivovať' }}</BaseButton>
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

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_REGEX = /^\+?\d{8,14}$/;
const ZIP_REGEX = /^\d{3}\s?\d{2}$/;

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
      accountActive: true,
      errors: {
        name: '',
        owner: '',
        email: '',
        phone: '',
        restaurantType: '',
        otherRestaurantType: '',
        cuisine: '',
        otherCuisine: '',
        addressStreet: '',
        addressCity: '',
        addressZip: '',
        seats: '',
        companyName: '',
        companyIco: '',
        companyDic: '',
        companyIcDph: '',
        billingIban: '',
        billingStreet: '',
        billingCity: '',
        billingZip: '',
        billingCountry: '',
        billingEmail: '',
      },
      hasTriedSave: false,
      restaurantTypeOptions: [],
      cuisineOptions: [],
      planInfo: {
        name: 'Platba - Najlepšia voľba',
        price: '47,97 €',
        nextBilling: '3. decembra 2025',
      },
      paymentMethod: {
        provider: 'Stripe',
        description: 'Bezpečná platobná brána pre online platby',
      },
      open_hours: [
        { day_of_week: 1, open_time: '08:00', close_time: '22:00', is_closed: false },
        { day_of_week: 2, open_time: '08:00', close_time: '22:00', is_closed: false },
        { day_of_week: 3, open_time: '08:00', close_time: '22:00', is_closed: false },
        { day_of_week: 4, open_time: '08:00', close_time: '22:00', is_closed: false },
        { day_of_week: 5, open_time: '08:00', close_time: '22:00', is_closed: false },
        { day_of_week: 6, open_time: '', close_time: '', is_closed: true },
        { day_of_week: 7, open_time: '', close_time: '', is_closed: true },
      ],
      dayLabels: {
        1: 'Pondelok',
        2: 'Utorok',
        3: 'Streda',
        4: 'Štvrtok',
        5: 'Piatok',
        6: 'Sobota',
        7: 'Nedeľa',
      },
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
  mounted() {
    this.loadOptions();
    this.loadProfile();
    this.loadOpenHours();
  },
  watch: {
    'profileForm.name': function () {
      this.revalidateField('name');
    },
    'profileForm.owner': function () {
      this.revalidateField('owner');
    },
    'profileForm.email': function () {
      this.revalidateField('email');
    },
    'profileForm.phone': function () {
      this.revalidateField('phone');
    },
    'profileForm.restaurantType': function (value) {
      if (value !== 'Ostatné') {
        this.profileForm.otherRestaurantType = '';
        this.errors.otherRestaurantType = '';
      }
      this.revalidateField('restaurantType');
      this.revalidateField('otherRestaurantType');
    },
    'profileForm.otherRestaurantType': function () {
      this.revalidateField('otherRestaurantType');
    },
    'profileForm.cuisine': function (value) {
      if (value !== 'Ostatné') {
        this.profileForm.otherCuisine = '';
        this.errors.otherCuisine = '';
      }
      this.revalidateField('cuisine');
      this.revalidateField('otherCuisine');
    },
    'profileForm.otherCuisine': function () {
      this.revalidateField('otherCuisine');
    },
    'profileForm.address.street': function () {
      this.revalidateField('addressStreet');
    },
    'profileForm.address.city': function () {
      this.revalidateField('addressCity');
    },
    'profileForm.address.zip': function () {
      this.revalidateField('addressZip');
    },
    'profileForm.seats': function () {
      this.revalidateField('seats');
    },
    'profileForm.billToCompany': function (value) {
      if (!value) {
        this.errors.companyName = '';
        this.errors.companyIco = '';
        this.errors.companyDic = '';
        this.errors.companyIcDph = '';
      }
      this.revalidateField('companyName');
      this.revalidateField('companyIco');
      this.revalidateField('companyDic');
      this.revalidateField('companyIcDph');
    },
    'profileForm.company.name': function () {
      this.revalidateField('companyName');
    },
    'profileForm.company.ico': function () {
      this.revalidateField('companyIco');
    },
    'profileForm.company.dic': function () {
      this.revalidateField('companyDic');
    },
    'profileForm.company.icDph': function () {
      this.revalidateField('companyIcDph');
    },
    'profileForm.billing.iban': function () {
      this.revalidateField('billingIban');
    },
    'profileForm.billing.street': function () {
      this.revalidateField('billingStreet');
    },
    'profileForm.billing.city': function () {
      this.revalidateField('billingCity');
    },
    'profileForm.billing.zip': function () {
      this.revalidateField('billingZip');
    },
    'profileForm.billing.country': function () {
      this.revalidateField('billingCountry');
    },
    'profileForm.billing.email': function () {
      this.revalidateField('billingEmail');
    },
    //zobrazenie loga (AI)
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
    async loadOptions() {
      var res = await this.$store.dispatch('settings/fetchOptions');
      this.restaurantTypeOptions = (res && res.restaurantTypes) || [{ type: 'Ostatné' }];
      this.cuisineOptions = (res && res.cuisineTypes) || [{ type: 'Ostatné' }];
    },
    async loadProfile() {
      var profile = await this.$store.dispatch('settings/fetchProfile');
      if (!profile) return;

      this.profileForm = profile || this.profileForm;
      if (profile.logo && this.profileForm.logo !== profile.logo) {
        this.profileForm.logo = profile.logo;
      }
      if (typeof profile.temporaryClosed === 'boolean') {
        this.temporaryClosure = profile.temporaryClosed;
      }
      if (typeof profile.isActive === 'boolean') {
        this.accountActive = profile.isActive;
      }
      this.resetErrors();
    },
    async saveAll() {
      var ok = await this.saveProfile();
      if (ok) {
        this.saveHours();
      }
    },
    resetProfile() {
      this.clearLogoPreview();
      this.resetErrors();
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
    async saveProfile() {
      this.hasTriedSave = true;
      if (!this.validateProfile()) {
        this.notifyErrors('Vyplňte prosím povinné údaje.');
        return false;
      }

      var saved = await this.$store.dispatch('settings/saveProfile', { profile: this.profileForm });
      if (!saved) return false;

      this.profileForm = saved;
      this.resetErrors();
      return true;
    },
    notifyErrors(message) {
      this.snackbar.notify({ message, variant: 'danger' });
    },
    resetErrors() {
      this.hasTriedSave = false;
      Object.keys(this.errors).forEach((key) => {
        this.errors[key] = '';
      });
    },
    revalidateField(field) {
      if (!this.hasTriedSave || !(field in this.errors)) return;
      this.errors[field] = this.getFieldError(field);
    },
    getFieldError(field) {
      var profile = this.profileForm;
      var normalized;

      switch (field) {
        case 'name':
          return profile.name && profile.name.trim() ? '' : 'Toto pole je povinné.';
        case 'owner':
          return profile.owner && profile.owner.trim() ? '' : 'Toto pole je povinné.';
        case 'email':
          normalized = (profile.email || '').trim();
          if (!normalized) return 'Email je povinný.';
          return EMAIL_REGEX.test(normalized) ? '' : 'Zadajte platný email.';
        case 'phone':
          normalized = (profile.phone || '').trim();
          if (!normalized) return 'Telefón je povinný.';
          return PHONE_REGEX.test(normalized.replace(/\s+/g, ''))
            ? ''
            : 'Telefón musí obsahovať 8 až 14 číslic (môže mať medzery a znak + na začiatku).';
        case 'restaurantType':
          return profile.restaurantType ? '' : 'Toto pole je povinné.';
        case 'otherRestaurantType':
          return profile.restaurantType === 'Ostatné' && !(profile.otherRestaurantType || '').trim()
            ? 'Uveďte vlastný typ reštaurácie.'
            : '';
        case 'cuisine':
          return profile.cuisine ? '' : 'Toto pole je povinné.';
        case 'otherCuisine':
          return profile.cuisine === 'Ostatné' && !(profile.otherCuisine || '').trim()
            ? 'Uveďte vlastný typ kuchyne.'
            : '';
        case 'addressStreet':
          return profile.address && (profile.address.street || '').trim() ? '' : 'Toto pole je povinné.';
        case 'addressCity':
          return profile.address && (profile.address.city || '').trim() ? '' : 'Toto pole je povinné.';
        case 'addressZip':
          normalized = profile.address ? (profile.address.zip || '').trim() : '';
          if (!normalized) return 'PSČ je povinné.';
          return ZIP_REGEX.test(normalized) ? '' : 'Zadajte platné PSČ.';
        case 'seats':
          normalized = profile.seats;
          if (normalized === '' || normalized === null || normalized === undefined) return '';
          return Number(normalized) >= 0 ? '' : 'Zadajte kladné číslo.';
        case 'companyName':
          return profile.billToCompany && !(profile.company?.name || '').trim() ? 'Zadajte názov firmy.' : '';
        case 'companyIco':
          return profile.billToCompany && !(profile.company?.ico || '').trim() ? 'IČO je povinné.' : '';
        case 'companyDic':
          return profile.billToCompany && !(profile.company?.dic || '').trim() ? 'DIČ je povinné.' : '';
        case 'companyIcDph':
          return '';
        case 'billingEmail':
          normalized = profile.billing ? (profile.billing.email || '').trim() : '';
          if (!normalized) return '';
          return EMAIL_REGEX.test(normalized) ? '' : 'Zadajte platný email.';
        case 'billingZip':
          normalized = profile.billing ? (profile.billing.zip || '').trim() : '';
          if (!normalized) return '';
          return ZIP_REGEX.test(normalized) ? '' : 'Zadajte platné PSČ.';
        default:
          return '';
      }
    },
    validateFields(fields) {
      var valid = true;
      fields.forEach((field) => {
        var message = this.getFieldError(field);
        if (field in this.errors) {
          this.errors[field] = message;
        }
        if (message) valid = false;
      });
      return valid;
    },
    validateProfile() {
      return this.validateFields([
        'name',
        'owner',
        'email',
        'phone',
        'restaurantType',
        'otherRestaurantType',
        'cuisine',
        'otherCuisine',
        'addressStreet',
        'addressCity',
        'addressZip',
        'seats',
        'companyName',
        'companyIco',
        'companyDic',
        'companyIcDph',
        'billingEmail',
        'billingZip',
      ]);
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
      if (!this.open_hours.length) return;
      const source = this.open_hours[0];
      this.open_hours.forEach((day) => {
        day.open_time = source.open_time;
        day.close_time = source.close_time;
        day.is_closed = source.is_closed;
      });
      this.snackbar.notify({ message: 'Hodiny skopírované na všetky dni.', variant: 'success' });
    },
    loadOpenHours() {
      this.$store.dispatch('restaurant/fetchOpenHours').then((hours) => {
        this.open_hours = hours;
      });
    },
    updateHour(dayOfWeek, key, value) {
      const index = dayOfWeek - 1;
      if (!this.open_hours[index]) return;
      this.open_hours[index][key] = value;
    },

    async toggleDay(dayOfWeek, isOpen) {
      const index = dayOfWeek - 1;
      if (!this.open_hours[index]) return;
      this.open_hours[index].is_closed = !isOpen;
      if (!isOpen) {
        this.open_hours[index].open_time = '';
        this.open_hours[index].close_time = '';
        await this.$store.dispatch('restaurant/removeDayOpenHours', {
          hours: this.open_hours,
          dayOfWeek,
        });
      }
    },
    dayLabel(day) {
      return this.dayLabels?.[day] || `Deň ${day}`;
    },
    saveHours() {
      this.$store.dispatch('restaurant/saveOpenHours', { hours: this.open_hours }).then((saved) => {
        this.open_hours = saved;
      });
    },
    deleteHours() {
      this.$store.dispatch('restaurant/deleteOpenHours').then((saved) => {
        this.open_hours = saved;
      });
    },
    saveLanguage() {
      const option = this.languageOptions.find((opt) => opt.value === this.language);
      this.snackbar.notify({
        message: `Jazyk nastavený na ${option?.label || 'vybraný jazyk'}.`,
        variant: 'success',
      });
    },
    handleTemporaryToggle(isOpen) {
      if (!isOpen) {
        this.pauseModalVisible = true;
        return;
      }
      this.setTemporaryClosure(false);
    },
    cancelPause() {
      this.pauseModalVisible = false;
      this.temporaryClosure = false;
    },
    confirmPause() {
      this.pauseModalVisible = false;
      this.setTemporaryClosure(true);
    },
    async setTemporaryClosure(value) {
      var res = await this.$store.dispatch('settings/setTemporaryClosure', { closed: value });
      if (res && typeof res.temporaryClosed === 'boolean') {
        this.temporaryClosure = !!res.temporaryClosed;
        return;
      }
      this.temporaryClosure = false;
    },
    async confirmDeactivate() {
      var ok = await this.$store.dispatch('settings/setAccountStatus', { active: !this.accountActive });
      if (!ok) return;
      this.deactivateModalVisible = false;
      this.accountActive = !this.accountActive;
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
    async finalizeDelete() {
      this.deleteErrors = { restaurantName: '', password: '' };
      if (this.deleteForm.restaurantName.trim() !== this.profileForm.name.trim()) {
        this.deleteErrors.restaurantName = 'Názov reštaurácie sa nezhoduje.';
      }
      if (!this.deleteForm.password.trim()) {
        this.deleteErrors.password = 'Zadajte heslo.';
      }
      if (this.deleteErrors.restaurantName || this.deleteErrors.password) return;

      var ok = await this.$store.dispatch('settings/deleteAccount', {
        restaurantName: this.deleteForm.restaurantName,
        password: this.deleteForm.password,
      });
      if (!ok) return;

      this.deleteVerifyVisible = false;
      this.$store.dispatch('user/odhlasenie', { silent: true }).finally(() => {
        this.$router.push({ name: 'admin-login' });
      });
    },
  },
};
</script>
