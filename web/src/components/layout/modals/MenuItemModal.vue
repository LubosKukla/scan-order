<template>
  <BaseModal v-model="proxyVisible" :title="modalTitle" class="max-h-[90vh]">
    <div class="flex flex-wrap gap-2 pb-4">
      <button
        v-for="tab in tabs"
        :key="tab.key"
        class="rounded-full px-4 py-1.5 text-sm font-semibold transition cursor-pointer"
        :class="tabClass(tab.key)"
        @click="activeTab = tab.key"
      >
        {{ tab.label }}
      </button>
    </div>

    <div v-if="activeTab === 'basic'" class="space-y-2">
      <div class="grid gap-2 md:grid-cols-2">
        <BaseInput label="Názov jedla" v-model="form.name" required />
        <BaseSelect
          label="Kategória"
          :options="categoryOptions"
          option-label-key="label"
          option-value-key="key"
          v-model="form.categoryKey"
          placeholder="Vyberte kategóriu"
          required
        />
      </div>

      <BaseTextarea label="Popis" v-model="form.description" rows="4" />

      <div class="grid gap-2 md:grid-cols-2">
        <BaseInput label="Čas prípravy" v-model="form.preparationTime" placeholder="15-20 min" />
        <BaseInput label="Alergény" v-model="form.allergens" placeholder="Laktóza, Lepok…" />
      </div>

      <BaseUpload label="Nahrať obrázok" v-model="form.imageFile" placeholder="Vyberte obrázok" accept="image/*" />

      <img
        v-if="previewUrl"
        :src="previewUrl"
        alt="Náhľad jedla"
        class="w-full rounded-2xl object-cover h-48 border border-ink/10"
      />

      <BaseCard class="space-y-6">
        <p class="font-semibold text-deep">Dostupnosť</p>
        <div class="flex flex-col gap-4">
          <BaseToggle
            v-model="form.visibleInMenu"
            label="Viditeľné v menu"
            class="flex flex-row-reverse w-full justify-between"
          />
        </div>
      </BaseCard>
    </div>
    <div v-else-if="activeTab === 'variants'" class="space-y-4">
      <div class="flex flex-wrap items-center gap-4">
        <div>
          <p class="text-sm text-deep">Pridajte rôzne verzie jedla (napr. Malá, Veľká, XXL).</p>
        </div>
        <BaseButton size="sm" class="ml-auto" icon="add" @click="addVariant">Pridať variant</BaseButton>
      </div>

      <BaseCard
        v-for="(variant, index) in variants"
        :key="variant.id"
        class="space-y-2 border border-deep/10 shadow-2xs"
      >
        <div class="flex items-start justify-between gap-4">
          <div>
            <p class="font-semibold text-deep">Variant</p>
            <p class="text-sm text-ink/60">#{{ index + 1 }}</p>
          </div>
          <button type="button" class="close-btn" @click="removeVariant(index)">&times;</button>
        </div>

        <BaseUpload
          label="Nahrat obrazok variantu"
          v-model="variant.imageFile"
          placeholder="Vyberte obrazok"
          accept="image/*"
        />

        <div class="grid gap-4 md:grid-cols-2">
          <BaseInput label="Nazov" v-model="variant.name" required placeholder="Double variant" />
          <BaseInput label="Cena (EUR)" v-model="variant.price" type="number" min="0" step="0.1" required />
        </div>
        <BaseInput label="Hmotnost" v-model="variant.weight" placeholder="200g" />
        <BaseTextarea
          label="Popis"
          v-model="variant.description"
          placeholder="Krátky opis rozdielu variantu"
          rows="2"
        />
      </BaseCard>

      <div v-if="!variants.length" class="p-3 text-center text-sm text-deep">
        Zatiaľ ste nepridali žiadny variant. Kliknite na
        <strong>Pridať variant</strong>
        a vyplňte informácie.
      </div>
    </div>
    <div v-else-if="activeTab === 'addons'" class="space-y-4">
      <div class="flex flex-wrap items-center gap-4">
        <div>
          <p class="text-sm text-deep">Prídavky, ktoré si zákazník môže objednať k jedlu.</p>
        </div>
        <BaseButton size="sm" class="ml-auto" icon="add" @click="addAddon">Pridať prídavok</BaseButton>
      </div>

      <BaseCard v-for="(addon, index) in addons" :key="addon.id" class="space-y-3 border border-deep/10 shadow-2xs">
        <div class="flex items-center justify-between gap-4">
          <p class="font-semibold text-deep">Prídavok #{{ index + 1 }}</p>
          <button type="button" class="close-btn" @click="removeAddon(index)">&times;</button>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
          <BaseInput label="Názov" v-model="addon.name" required placeholder="Extra syr" />
          <BaseInput label="Cena (€)" v-model="addon.price" type="number" min="0" step="0.1" required />
        </div>

        <div class="flex items-center justify-between gap-4">
          <BaseToggle
            v-model="addon.available"
            label="Dostupné"
            class="flex flex-row-reverse items-center gap-2 text-sm"
          />
        </div>
      </BaseCard>

      <div v-if="!addons.length" class="p-3 text-center text-sm text-deep">
        Zatiaľ ste nepridali žiadny prídavok. Kliknite na
        <strong>Pridať prídavok</strong>
        a vyplňte informácie.
      </div>
    </div>

    <template #footer>
      <div class="flex justify-end gap-3">
        <BaseButton variant="secondary" @click="handleCancel">Zrušiť</BaseButton>
        <BaseButton @click="handleSubmit">{{ mode === 'edit' ? 'Uložiť zmeny' : 'Pridať jedlo' }}</BaseButton>
      </div>
    </template>
  </BaseModal>
</template>

<script>
//generated by ai
import BaseModal from '@/components/global/containers/BaseModal.vue';
import BaseInput from '@/components/global/inputs/BaseInput.vue';
import BaseTextarea from '@/components/global/inputs/BaseTextarea.vue';
import BaseSelect from '@/components/global/inputs/BaseSelect.vue';
import BaseToggle from '@/components/global/inputs/BaseToggle.vue';
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import BaseUpload from '@/components/global/inputs/BaseUpload.vue';
import { CATEGORY_ICON_OPTIONS } from '@/constants/categoryIcons';

export default {
  name: 'MenuItemModal',
  components: { BaseModal, BaseInput, BaseTextarea, BaseSelect, BaseToggle, BaseCard, BaseButton, BaseUpload },
  props: {
    modelValue: {
      type: Boolean,
      default: false,
    },
    mode: {
      type: String,
      default: 'create',
    },
    item: {
      type: Object,
      default: null,
    },
  },
  emits: ['update:modelValue', 'save'],
  data() {
    return {
      form: {
        name: '',
        categoryKey: '',
        description: '',
        preparationTime: '',
        allergens: '',
        imageFile: null,
        visibleInMenu: true,
      },
      variants: [],
      addons: [],
      activeTab: 'basic',
      previewUrl: '',
      objectUrl: null,
      tabs: [
        { key: 'basic', label: 'Základné' },
        { key: 'variants', label: 'Varianty' },
        { key: 'addons', label: 'Prídavky' },
      ],
    };
  },
  computed: {
    proxyVisible: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit('update:modelValue', value);
      },
    },
    modalTitle() {
      return this.mode === 'edit' ? 'Upraviť jedlo' : 'Pridať nové jedlo';
    },
    categoryOptions() {
      return CATEGORY_ICON_OPTIONS.map((option) => ({
        key: option.key,
        label: option.label,
      }));
    },
  },
  watch: {
    modelValue(value) {
      if (value) {
        this.syncForm();
      }
    },
    item: {
      deep: true,
      handler() {
        if (this.modelValue) {
          this.syncForm();
        }
      },
    },
    'form.imageFile'(file) {
      this.setPreviewFromFile(file);
    },
  },
  methods: {
    tabClass(key) {
      if (this.activeTab === key) {
        return 'bg-primary/10 text-primary border border-primary/30';
      }
      return ' text-deep hover:bg-ink/50 disabled:opacity-60 disabled:cursor-not-allowed';
    },
    syncForm() {
      const source = this.item || {};
      this.form = {
        name: source.name || '',
        categoryKey: source.categoryKey || '',
        description: source.description || '',
        preparationTime: source.preparationTime || '',
        allergens: source.allergens || '',
        imageFile: source.image || source.imageFile || null,
        visibleInMenu: source.visibleInMenu !== undefined ? source.visibleInMenu : true,
      };
      this.variants = Array.isArray(source.variants)
        ? source.variants.map((variant) => this.createVariant(variant))
        : [];
      this.addons = Array.isArray(source.addons) ? source.addons.map((addon) => this.createAddon(addon)) : [];
      this.setPreviewFromFile(this.form.imageFile);
      this.activeTab = 'basic';
    },
    handleCancel() {
      this.proxyVisible = false;
    },
    handleSubmit() {
      this.$emit('save', { ...this.form, variants: this.variants, addons: this.addons, mode: this.mode });
      this.proxyVisible = false;
    },
    setPreviewFromFile(file) {
      this.clearPreviewUrl();
      if (file instanceof File) {
        this.objectUrl = URL.createObjectURL(file);
        this.previewUrl = this.objectUrl;
      } else if (typeof file === 'string') {
        this.previewUrl = file;
      } else {
        this.previewUrl = '';
      }
    },
    clearPreviewUrl() {
      if (this.objectUrl) {
        URL.revokeObjectURL(this.objectUrl);
        this.objectUrl = null;
      }
    },
    createVariant(initial = {}) {
      return {
        id: initial.id || `${Date.now()}-${Math.random().toString(16).slice(2)}`,
        name: initial.name || '',
        price: initial.price || '',
        weight: initial.weight || '',
        description: initial.description || '',
        imageFile: initial.image || initial.imageFile || null,
      };
    },
    addVariant() {
      this.variants.push(this.createVariant());
    },
    removeVariant(index) {
      this.variants.splice(index, 1);
    },
    createAddon(initial = {}) {
      return {
        id: initial.id || `${Date.now()}-${Math.random().toString(16).slice(2)}`,
        name: initial.name || '',
        price: initial.price || '',
        available: initial.available !== undefined ? initial.available : true,
      };
    },
    addAddon() {
      this.addons.push(this.createAddon());
    },
    removeAddon(index) {
      this.addons.splice(index, 1);
    },
  },
  beforeUnmount() {
    this.clearPreviewUrl();
  },
};
</script>

<style lang="scss" scoped>
.close-btn {
  font-size: 1.5rem;
  line-height: 1;
  background: transparent;
  border: none;
  cursor: pointer;
  color: #0f172a;
  transition: transform 0.15s ease, color 0.15s ease;

  &:hover {
    color: #0ccaba;
    transform: scale(1.1);
  }
}
</style>
