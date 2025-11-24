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
        class="w-full rounded-2xl object-cover h-48 border border-deep/10"
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
            <p class="text-sm text-deep/60">#{{ index + 1 }}</p>
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
          <p class="text-sm text-deep">Doplnky, ktoré si zákazník môže objednať k jedlu.</p>
        </div>
        <BaseButton size="sm" class="ml-auto" icon="add" @click="addAddon">Pridať doplnok</BaseButton>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-deep/70">
          Testovacie dáta sú zoradené podľa kategórie (Nápoje, Dezerty, Prílohy).
        </p>

        <BaseCard
          v-for="(addon, index) in addons"
          :key="addon.id"
          class="space-y-3 border border-deep/10 shadow-2xs"
        >
          <div class="flex items-start justify-between gap-2">
            <p class="text-sm font-semibold text-deep">Doplnok #{{ index + 1 }}</p>
            <button type="button" class="close-btn" @click="removeAddon(index)">&times;</button>
          </div>

          <BaseSelect
            :model-value="addon.key"
            @update:modelValue="(val) => handleAddonSelect(index, val)"
            label="Vybrať doplnok"
            :options="addonOptions"
            option-label-key="label"
            option-value-key="key"
            placeholder="Vyberte doplnok"
          />

          <div class="grid gap-3 md:grid-cols-2">
            <BaseInput label="Názov doplnku" v-model="addon.name" placeholder="Názov" />
            <BaseInput label="Cena (€)" v-model="addon.price" type="number" min="0" step="0.1" />
          </div>

          <BaseToggle
            v-model="addon.available"
            label="Viditeľné v ponuke"
            class="flex flex-row-reverse items-center justify-between"
          />

          <BaseCard class="space-y-2 border border-deep/10 bg-deep/5">
            <p class="text-sm font-semibold text-deep">Náhľad</p>
            <div class="aspect-[16/10] overflow-hidden rounded-xl border border-deep/10 bg-white">
              <img
                v-if="addon.image"
                :src="addon.image"
                :alt="addon.name"
                class="h-full w-full object-cover"
              />
              <div v-else class="flex h-full items-center justify-center text-sm text-deep/50">Bez obrázka</div>
            </div>
            <div class="space-y-1 text-sm text-deep">
              <p class="text-base font-semibold text-deep">{{ addon.name || 'Bez názvu' }}</p>
              <p class="text-deep/70">
                {{ addon.category || 'Kategória' }} · {{ addon.price ? formatPrice(addon.price) : '—' }}
              </p>
              <p class="text-deep/70">{{ addon.description || 'Bez popisu' }}</p>
            </div>
          </BaseCard>
        </BaseCard>

        <div v-if="!addons.length" class="p-3 text-center text-sm text-deep">
          Kliknite na <strong>Pridať doplnok</strong> a vyberte položku pre tento produkt.
        </div>
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
      availableAddons: [
        {
          key: 'kofola',
          name: 'Kofola 0.5l',
          category: 'Nápoje',
          price: 2.4,
          description: 'Chladená kofola, ideálna k menu.',
          image: 'https://images.unsplash.com/photo-1527169402691-feff5539e52c?auto=format&fit=crop&w=800&q=80',
        },
        {
          key: 'espresso',
          name: 'Espresso',
          category: 'Nápoje',
          price: 1.8,
          description: 'Krátka káva s plnou arómou.',
          image: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=800&q=80',
        },
        {
          key: 'colsalat',
          name: 'Coleslaw',
          category: 'Prílohy',
          price: 1.5,
          description: 'Jemný kapustový šalát so zálievkou.',
          image: 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=800&q=80',
        },
        {
          key: 'hranolky',
          name: 'Hranolky',
          category: 'Prílohy',
          price: 2.2,
          description: 'Chrumkavé hranolky soľ/bez soli.',
          image: 'https://images.unsplash.com/photo-1585238341986-2650c522f1c3?auto=format&fit=crop&w=800&q=80',
        },
        {
          key: 'cheesecake',
          name: 'Cheesecake',
          category: 'Dezerty',
          price: 3.9,
          description: 'Krémový cheesecake s ovocnou polevou.',
          image: 'https://images.unsplash.com/photo-1505253758473-96b7015fcd40?auto=format&fit=crop&w=800&q=80',
        },
      ],
      activeTab: 'basic',
      previewUrl: '',
      objectUrl: null,
      tabs: [
        { key: 'basic', label: 'Základné' },
        { key: 'variants', label: 'Varianty' },
        { key: 'addons', label: 'Doplnky' },
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
    addonOptions() {
      return this.availableAddons
        .slice()
        .sort((a, b) => a.category.localeCompare(b.category) || a.name.localeCompare(b.name))
        .map((addon) => ({
          key: addon.key,
          label: `${addon.category} · ${addon.name} (${this.formatPrice(addon.price)})`,
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
      return ' text-deep hover:bg-deep/5 disabled:opacity-60 disabled:cursor-not-allowed';
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
        key: initial.key || '',
        name: initial.name || '',
        price: initial.price || '',
        category: initial.category || 'Doplnky',
        description: initial.description || '',
        image: initial.image || '',
        available: initial.available !== undefined ? initial.available : true,
      };
    },
    addAddon() {
      const template = this.availableAddons[0] || {};
      const newAddon = this.createAddon(template);
      this.addons.push(newAddon);
    },
    removeAddon(index) {
      this.addons.splice(index, 1);
    },
    handleAddonSelect(index, key) {
      const current = this.addons[index] || {};
      const base = this.availableAddons.find((item) => item.key === key) || { key };
      const updated = this.createAddon({
        ...current,
        ...base,
        id: current.id,
        key,
        available: current.available,
      });
      this.addons.splice(index, 1, updated);
    },
    formatPrice(value) {
      const number = Number(value) || 0;
      return `${number.toFixed(2)} €`;
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
