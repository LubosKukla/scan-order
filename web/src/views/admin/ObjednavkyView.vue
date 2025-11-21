<template>
  <section class="space-y-8">
    <AdminPageHeader title="Objednávky" subtitle="Spravujte a sledujte všetky objednávky reštaurácie v reálnom čase.">
      <template #actions>
        <BaseButton icon="add" @click="openCreateModal">Pridať objednávku</BaseButton>
      </template>
    </AdminPageHeader>

    <div class="space-y-6 bg-ink border-transparent rounded-3xl">
      <div class="flex flex-wrap gap-3">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          type="button"
          class="rounded-full px-5 py-2 text-sm font-semibold transition cursor-pointer"
          :class="tabClass(tab.key)"
          @click="activeTab = tab.key"
        >
          {{ tab.label }}
        </button>
      </div>

      <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div v-for="card in statCards" :key="card.key" class="flex flex-col gap-3">
          <BaseCard>
            <div class="flex items-center md:justify-between justify-center sm:gap-4">
              <div class="flex flex-col items-start justify-between gap-1">
                <p class="text-xs font-semibold text-left text-deep">
                  {{ card.label }}
                </p>
                <div class="space-y-1">
                  <p class="text-md font-semibold text-deep">
                    {{ card.value }}
                  </p>
                </div>
              </div>

              <span class="rounded-xl hidden sm:block bg-ink/60 p-3 text-primary">
                <font-awesome-icon :icon="card.icon" class="h-5 w-5" />
              </span>
            </div>
          </BaseCard>
        </div>
      </div>
    </div>

    <BaseCard class="space-y-4">
      <div class="grid gap-2 md:grid-cols-[2fr_1fr_1fr]">
        <BaseInput v-model="filters.search" type="search" placeholder="Hľadať podľa čísla objednávky alebo mena..." />
        <BaseSelect v-model="filters.status" :options="statusOptions" placeholder="Všetky objednávky" />
        <BaseSelect v-model="filters.day" :options="dayOptions" placeholder="Dnes" />
      </div>
    </BaseCard>

    <BaseCard class="space-y-4">
      <div>
        <p class="text-lg font-semibold text-deep">Zoznam objednávok</p>
        <p class="text-sm text-deep/70">Zobrazuje sa {{ filteredOrders.length }} z {{ orders.length }} objednávok</p>
      </div>

      <div class="hidden xl:block overflow-x-auto">
        <table class="min-w-full divide-y divide-ink/20 text-sm">
          <thead>
            <tr class="text-left text-xs font-semibold uppercase text-deep/60">
              <th class="padding-item">Číslo obj.</th>
              <th class="padding-item">Zákazník / Stôl</th>
              <th class="padding-item">Typ</th>
              <th class="padding-item">Zostávajúci čas</th>
              <th class="padding-item">Stav</th>
              <th class="padding-item">Suma</th>
              <th class="padding-item text-right">Akcie</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in filteredOrders" :key="order.id" class="text-deep hover:bg-ink/30 border-t border-t-ink">
              <td class="padding-item font-semibold">#{{ order.id }}</td>
              <td class="padding-item">
                <p class="font-semibold">{{ order.customer }}</p>
                <p class="text-xs text-deep/60">{{ order.location }}</p>
              </td>
              <td class="padding-item">
                <OrderTypeBadge :type="order.type" />
              </td>
              <td class="padding-item">
                <div class="flex items-center gap-2 text-sm cursor-pointer select-none" @dblclick="addFiveMinutes(order)">
                  <font-awesome-icon :icon="faClock" class="text-deep/50" />
                  <span>{{ formatTime(order.remainingSeconds) }}</span>
                </div>
              </td>
              <td class="padding-item">
                <OrderStatusBadge :status="order.status" />
              </td>
              <td class="padding-item font-semibold">{{ formatAmount(order.total) }}</td>
              <td class="padding-item text-right">
                <div class="flex items-center justify-end gap-2">
                  <BaseButton
                    variant="noBackground"
                    icon="view"
                    class="text-deep/70 hover:text-primary"
                    @click="openOrderPreview(order)"
                  />
                  <BaseButton variant="noBackground" icon="confirm" class="text-emerald-600 hover:text-emerald-700" />
                  <BaseButton
                    variant="noBackground"
                    icon="close"
                    class="text-danger hover:text-danger"
                    @click="openDeleteModal(order)"
                  />
                </div>
              </td>
            </tr>
            <tr v-if="!filteredOrders.length">
              <td colspan="7" class="px-4 py-8 text-center text-sm text-deep/60">
                Žiadne objednávky pre zvolené filtre.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="space-y-3 xl:hidden">
        <BaseCard
          v-for="order in filteredOrders"
          :key="`mobile-${order.id}`"
          class="border border-ink/20 bg-white shadow-sm space-y-3 p-4"
          no-padding="true"
        >
          <div class="flex items-center justify-between gap-2 flex-col sm:flex-row">
            <p class="text-sm font-semibold text-deep/60">#{{ order.id }}</p>
            <OrderStatusBadge :status="order.status" />
          </div>
          <div>
            <p class="font-semibold text-deep">{{ order.customer }}</p>
            <p class="text-xs text-deep/60">{{ order.location }}</p>
          </div>
          <OrderTypeBadge :type="order.type" />
          <div class="flex items-center gap-2 cursor-pointer select-none" @dblclick="addFiveMinutes(order)">
            <font-awesome-icon :icon="faClock" class="text-deep/80" />
            <span class="text-sm text-deep/80">{{ formatTime(order.remainingSeconds) }}</span>
          </div>
          <p class="text-sm font-semibold text-deep">{{ formatAmount(order.total) }}</p>
          <div class="flex items-center justify-end gap-2">
            <BaseButton
              variant="noBackground"
              icon="view"
              class="text-deep/70 hover:text-primary"
              @click="openOrderPreview(order)"
            />
            <BaseButton variant="noBackground" icon="confirm" class="text-emerald-600 hover:text-emerald-700" />
            <BaseButton
              variant="noBackground"
              icon="close"
              class="text-danger hover:text-danger"
              @click="openDeleteModal(order)"
            />
          </div>
        </BaseCard>
        <p v-if="!filteredOrders.length" class="text-center text-sm text-deep/60">
          Žiadne objednávky pre zvolené filtre.
        </p>
      </div>
    </BaseCard>

    <BaseModal v-model="createModalVisible" title="Pridať novú objednávku">
      <div class="space-y-5">
        <p class="text-sm text-deep/70">Vytvorte objednávku manuálne.</p>

        <div class="space-y-2">
          <p class="text-sm font-semibold text-deep/70">Typ objednávky</p>
          <div class="grid grid-cols-3 gap-3">
            <button
              v-for="type in orderTypeOptions"
              :key="type.key"
              type="button"
              class="rounded-xl cursor-pointer border-2 px-4 py-3 text-sm font-semibold transition flex flex-col items-center gap-1 hover:border-primary/30 hover:bg-primary/10 hover:text-primary'"
              :class="
                createForm.type === type.key ? 'border-primary bg-primary/10 text-primary' : 'border-ink text-deep/70'
              "
              @click="createForm.type = type.key"
            >
              <font-awesome-icon :icon="type.icon" class="text-xl" />
              {{ type.label }}
            </button>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-3">
          <BaseInput label="Meno zákazníka" v-model="createForm.customer" placeholder="Zadajte meno" />
          <BaseInput label="Telefón" v-model="createForm.phone" placeholder="+421 xxx xxx xxx" />
          <BaseSelect
            label="Stôl"
            required
            v-model="createForm.table"
            :options="tableOptions"
            placeholder="Vyberte stôl"
          />
          <BaseSelect
            label="Spôsob platby"
            v-model="createForm.payment"
            :options="paymentOptions"
            placeholder="Vyberte platbu"
          />
        </div>

        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-deep">
              Pridať položku
              <span class="text-danger">*</span>
            </p>
            <BaseButton variant="secondary" :disabled="!canAddItem" @click="addSelectedItemToForm">Pridať</BaseButton>
          </div>
          <div class="flex flex-wrap gap-2">
            <button
              v-for="category in menuCategories"
              :key="category.key"
              type="button"
              class="px-4 py-2 rounded-lg cursor-pointer text-xs font-semibold transition hover:bg-deep/20"
              :class="selectedCategoryKey === category.key ? 'bg-primary text-white' : 'bg-ink text-deep/70'"
              @click="selectCategory(category.key)"
            >
              {{ category.label }}
            </button>
          </div>
          <div class="grid sm:grid-cols-2 gap-2">
            <button
              v-for="item in menuItemsForCategory"
              :key="item.id"
              type="button"
              class="rounded-xl cursor-pointer border px-4 py-3 text-left text-sm transition hover:border-primary/30 hover:bg-primary/10 hover:text-primary"
              :class="selectedMenuItem?.id === item.id ? 'border-primary bg-primary/10' : 'border-ink bg-white'"
              @click="selectMenuItem(item)"
            >
              <p class="font-semibold text-deep">{{ item.name }}</p>
              <p class="text-xs text-deep/60">{{ formatAmount(item.price) }}</p>
            </button>
          </div>

          <div v-if="selectedMenuItem" class="space-y-3 rounded-2xl border border-ink p-4 bg-white">
            <p class="text-sm font-semibold text-deep">{{ selectedMenuItem.name }}</p>
            <BaseSelect
              v-if="variantOptions.length"
              label="Varianta"
              :options="variantOptions"
              v-model="selectedVariantId"
            />
            <div v-if="selectedMenuItem.removables?.length" class="space-y-2">
              <p class="text-xs font-semibold text-deep/70">Odstrániť</p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="ingredient in selectedMenuItem.removables"
                  :key="ingredient.id"
                  type="button"
                  class="px-3 py-1 rounded-full border text-xs"
                  :class="
                    selectedRemovals.includes(ingredient.id)
                      ? 'border-primary bg-primary/10 text-primary'
                      : 'border-ink text-deep/70'
                  "
                  @click="toggleRemoval(ingredient.id)"
                >
                  {{ ingredient.label }}
                </button>
              </div>
            </div>
            <div v-if="selectedMenuItem.extras?.length" class="space-y-2">
              <p class="text-xs font-semibold text-deep/70">Pridať</p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="extra in selectedMenuItem.extras"
                  :key="extra.id"
                  type="button"
                  class="px-3 py-1 rounded-full border text-xs"
                  :class="
                    selectedExtras.includes(extra.id)
                      ? 'border-primary bg-primary/10 text-primary'
                      : 'border-ink text-deep/70'
                  "
                  @click="toggleExtra(extra.id)"
                >
                  {{ extra.label }} +{{ formatAmount(extra.price) }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <OrderItemsTable
          :items="createForm.items"
          :total="createFormTotal"
          title="Vybrané položky"
          removable
          @remove="removeFormItem"
        />

        <BaseTextarea label="Poznámka" v-model="createForm.note" placeholder="Poznámka k objednávke..." rows="3" />
      </div>

      <template #footer>
        <BaseButton variant="secondary" @click="closeCreateModal">Zrušiť</BaseButton>
        <BaseButton icon="add" :disabled="!canCreateOrder" @click="createOrder">Vytvoriť objednávku</BaseButton>
      </template>
    </BaseModal>

    <BaseModal v-model="previewVisible" :title="previewTitle">
      <div v-if="selectedOrder" class="space-y-5">
        <p class="text-sm text-deep/70">Objednávka vytvorená: {{ formatDateTime(selectedOrder.createdAt) }}</p>

        <BaseCard class="space-y-4 border border-primary/30 bg-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-semibold text-deep">Zostávajúci čas:</p>
              <p class="text-xs text-deep/60">Priebežne aktualizujte podľa potreby</p>
            </div>
            <div class="flex items-center gap-2 text-primary font-semibold text-xl">
              <font-awesome-icon :icon="faClock" />
              <span>{{ formatTime(selectedOrder.remainingSeconds) }}</span>
            </div>
          </div>
          <div class="h-2 rounded-full bg-ink/30 overflow-hidden">
            <div class="h-full bg-primary transition-all" :style="{ width: progressPercent + '%' }"></div>
          </div>
          <div class="flex flex-wrap gap-2">
            <BaseButton variant="secondary" @click="adjustTime(300)">+5 minút</BaseButton>
            <BaseButton variant="secondary" @click="adjustTime(600)">+10 minút</BaseButton>
          </div>
        </BaseCard>

        <div class="grid gap-4 md:grid-cols-2">
          <div class="flex flex-col gap-1 items-start">
            <p class="text-sm font-semibold text-deep/80">Zákazník</p>
            <p class="text-lg font-semibold text-deep">{{ selectedOrder.customer }}</p>
            <p class="text-xs text-deep/70">{{ selectedOrder.location }}</p>
          </div>
          <div class="flex flex-col gap-1 items-start">
            <p class="text-sm font-semibold text-deep/80">Platba</p>
            <p class="text-lg font-semibold text-deep">{{ selectedOrder.payment }}</p>
          </div>
          <div class="flex flex-col gap-1 items-start">
            <p class="text-sm font-semibold text-deep/80">Typ objednávky</p>
            <OrderTypeBadge :type="selectedOrder.type" />
          </div>
          <div class="flex flex-col gap-1 items-start">
            <p class="text-sm font-semibold text-deep/80">Aktuálny stav</p>
            <OrderStatusBadge :status="selectedOrder.status" />
          </div>
        </div>

        <OrderItemsTable :items="selectedOrder.items" :total="selectedOrder.total" title="Objednané položky" />

        <div class="space-y-2">
          <p class="text-sm font-semibold text-deep/60">Aktualizovať stav</p>
          <BaseSelect v-model="statusUpdate" :options="statusUpdateOptions" placeholder="Vyberte stav" />
        </div>
      </div>
      <template #footer>
        <div class="flex flex-wrap gap-3">
          <BaseButton variant="secondary" icon="qrcode">Zobraziť QR stola</BaseButton>
          <BaseButton variant="secondary" icon="print">Vytlačiť účtenku</BaseButton>
          <BaseButton @click="previewVisible = false">Zavrieť</BaseButton>
        </div>
      </template>
    </BaseModal>

    <BaseModal v-model="deleteModalVisible" title="Vymazať objednávku?">
      <div class="space-y-4" v-if="selectedOrder">
        <div>
          <p class="text-sm text-deep">
            Ste si istý, že chcete odstrániť objednávku
            <strong>#{{ selectedOrder.id }}</strong>
            ?
          </p>
          <p class="text-xs text-deep/60">Táto akcia sa nedá vrátiť.</p>
        </div>

        <OrderItemsTable :items="selectedOrder.items" :total="selectedOrder.total" title="Obsah objednávky" />
      </div>
      <template #footer>
        <BaseButton variant="secondary" @click="deleteModalVisible = false">Zrušiť</BaseButton>
        <BaseButton icon="trash" @click="confirmDelete">Odstrániť</BaseButton>
      </template>
    </BaseModal>
  </section>
</template>

<script>
//generated by AI
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
  faBell,
  faBolt,
  faCircleCheck,
  faEuroSign,
  faClock,
  faUtensils,
  faBox,
  faMotorcycle,
} from '@fortawesome/free-solid-svg-icons';
import AdminPageHeader from '@/components/layout/funkcionality/AdminPageHeader.vue';
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseInput from '@/components/global/inputs/BaseInput.vue';
import BaseSelect from '@/components/global/inputs/BaseSelect.vue';
import BaseTextarea from '@/components/global/inputs/BaseTextarea.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';
import OrderTypeBadge from '@/components/global/orders/OrderTypeBadge.vue';
import OrderStatusBadge from '@/components/global/orders/OrderStatusBadge.vue';
import OrderItemsTable from '@/components/global/orders/OrderItemsTable.vue';
import { formatCurrency as formatCurrencyValue } from '@/utils/formatters';

const minutesAgo = (mins) => {
  const date = new Date();
  date.setMinutes(date.getMinutes() - mins);
  return date.toISOString();
};

const SAMPLE_ORDERS = [
  {
    id: '10043',
    customer: 'Jana Nováková',
    location: 'Stôl 3 (Terasa)',
    type: 'dineIn',
    status: 'preparing',
    remainingSeconds: 496,
    total: 24.5,
    createdAt: minutesAgo(20),
    payment: 'Hotovosť',
    items: [
      { name: 'Steak s omáčkou', variant: 'Medium', quantity: 1, price: 24.5 },
      { name: 'Pivo', variant: '0.5L', quantity: 1, price: 4 },
    ],
  },
  {
    id: '10044',
    customer: 'Peter Horváth',
    location: 'Stôl 7',
    type: 'dineIn',
    status: 'preparing',
    remainingSeconds: 102,
    total: 28.5,
    createdAt: minutesAgo(15),
    payment: 'Karta',
    items: [{ name: 'Burger Double', variant: 'S hranolkami', quantity: 1, price: 28.5 }],
  },
  {
    id: '10045',
    customer: 'Objednávka na odber',
    location: 'Výdajné okno',
    type: 'takeout',
    status: 'new',
    remainingSeconds: 704,
    total: 45.8,
    createdAt: minutesAgo(30),
    payment: 'Online',
    items: [
      { name: 'Šalát Caesar', variant: 'Bez krutónov', quantity: 2, price: 9.9 },
      { name: 'Limonáda', variant: '0.5L', quantity: 2, price: 3.5 },
    ],
  },
  {
    id: '10042',
    customer: 'Martina Kováčová',
    location: 'Stôl 5',
    type: 'dineIn',
    status: 'completed',
    remainingSeconds: 0,
    total: 32.4,
    createdAt: minutesAgo(60),
    payment: 'Hotovosť',
    items: [{ name: 'Pizza Margherita', variant: '32 cm', quantity: 1, price: 32.4 }],
  },
  {
    id: '10046',
    customer: 'Tomáš Lukáč',
    location: 'Hlavná 45, Bratislava',
    type: 'delivery',
    status: 'new',
    remainingSeconds: 1784,
    total: 38.9,
    createdAt: minutesAgo(5),
    payment: 'Karta',
    items: [{ name: 'Donáškový box', variant: 'Menu A', quantity: 1, price: 38.9 }],
  },
];

const MENU_CATEGORIES = [
  {
    key: 'mains',
    label: 'Hlavné jedlá',
    items: [
      {
        id: 'gnocchi',
        name: 'Gnocchi so špenátom',
        price: 12.5,
        variants: [
          { id: 'medium', label: 'Medium', price: 0 },
          { id: 'large', label: 'Large +2 €', price: 2 },
        ],
        removables: [
          { id: 'parmesan', label: 'Parmezán' },
          { id: 'garlic', label: 'Cesnak' },
        ],
        extras: [
          { id: 'extraSauce', label: 'Extra omáčka', price: 1.5 },
          { id: 'bacon', label: 'Slanina', price: 2 },
        ],
      },
      {
        id: 'steak',
        name: 'Steak s omáčkou',
        price: 24.5,
        variants: [
          { id: 'rare', label: 'Rare', price: 0 },
          { id: 'medium', label: 'Medium', price: 0 },
          { id: 'well', label: 'Well done', price: 0 },
        ],
        removables: [
          { id: 'pepper', label: 'Korenie' },
          { id: 'butter', label: 'Bylinkové maslo' },
        ],
        extras: [
          { id: 'extraFries', label: 'Extra hranolky', price: 2.5 },
          { id: 'grilledVeg', label: 'Grilovaná zelenina', price: 3 },
        ],
      },
    ],
  },
  {
    key: 'dessert',
    label: 'Dezerty',
    items: [
      {
        id: 'tiramisu',
        name: 'Tiramisu',
        price: 6.5,
        variants: [],
        removables: [],
        extras: [{ id: 'extraCocoa', label: 'Extra kakao', price: 0.5 }],
      },
      {
        id: 'cheesecake',
        name: 'Cheesecake',
        price: 7.5,
        variants: [
          { id: 'blueberry', label: 'Čučoriedkový', price: 0 },
          { id: 'saltedCaramel', label: 'Slaný karamel', price: 1 },
        ],
        removables: [],
        extras: [{ id: 'whippedCream', label: 'Šľahačka', price: 0.5 }],
      },
    ],
  },
];

const ORDER_TYPE_OPTIONS = [
  { key: 'dineIn', label: 'Na mieste', icon: faUtensils },
  { key: 'takeout', label: 'Odber', icon: faBox },
  { key: 'delivery', label: 'Donáška', icon: faMotorcycle },
];

const TABLE_OPTIONS = [
  { label: 'Stôl 1', value: 'table1' },
  { label: 'Stôl 2', value: 'table2' },
  { label: 'Stôl 3', value: 'table3' },
  { label: 'Stôl 4 - terasa', value: 'table4' },
  { label: 'Výdajné okno', value: 'takeout' },
  { label: 'Donáška', value: 'delivery' },
];

const PAYMENT_OPTIONS = [
  { label: 'Hotovosť', value: 'cash' },
  { label: 'Karta', value: 'card' },
  { label: 'Online platba', value: 'online' },
];

const STATUS_OPTIONS = [
  { label: 'Všetky objednávky', value: 'all' },
  { label: 'Nové', value: 'new' },
  { label: 'Pripravuje sa', value: 'preparing' },
  { label: 'Pripravené', value: 'ready' },
  { label: 'Dokončené', value: 'completed' },
  { label: 'Zrušené', value: 'cancelled' },
];

const DAY_OPTIONS = [
  { label: 'Dnes', value: 'today' },
  { label: 'Včera', value: 'yesterday' },
  { label: 'Tento týždeň', value: 'week' },
  { label: 'Všetky', value: 'all' },
];

export default {
  name: 'AdminObjednavkyView',
  components: {
    AdminPageHeader,
    BaseCard,
    BaseInput,
    BaseSelect,
    BaseTextarea,
    BaseButton,
    BaseModal,
    OrderTypeBadge,
    OrderStatusBadge,
    OrderItemsTable,
    FontAwesomeIcon,
  },
  data() {
    return {
      tabs: [
        { key: 'all', label: 'Všetky' },
        { key: 'dineIn', label: 'Na mieste' },
        { key: 'takeout', label: 'Na odber' },
        { key: 'delivery', label: 'Donáška' },
      ],
      activeTab: 'all',
      orders: SAMPLE_ORDERS,
      filters: {
        search: '',
        status: 'all',
        day: 'today',
      },
      statusOptions: STATUS_OPTIONS,
      dayOptions: DAY_OPTIONS,
      faClock,
      previewVisible: false,
      selectedOrder: null,
      statusUpdate: 'new',
      deleteModalVisible: false,
      createModalVisible: false,
      createForm: {
        type: 'dineIn',
        customer: '',
        phone: '',
        table: '',
        payment: 'online',
        note: '',
        items: [],
      },
      menuCategories: MENU_CATEGORIES,
      selectedCategoryKey: MENU_CATEGORIES[0].key,
      selectedMenuItem: null,
      selectedVariantId: null,
      selectedExtras: [],
      selectedRemovals: [],
      orderTypeOptions: ORDER_TYPE_OPTIONS,
      tableOptions: TABLE_OPTIONS,
      paymentOptions: PAYMENT_OPTIONS,
    };
  },
  computed: {
    statCards() {
      return [
        {
          key: 'new',
          label: 'Nové objednávky',
          value: this.orders.filter((o) => o.status === 'new').length,
          icon: faBell,
        },
        {
          key: 'active',
          label: 'Aktívne objednávky',
          value: this.orders.filter((o) => ['new', 'preparing', 'ready'].includes(o.status)).length,
          icon: faBolt,
        },
        {
          key: 'completed',
          label: 'Dokončené dnes',
          value: this.orders.filter((o) => o.status === 'completed').length,
          icon: faCircleCheck,
        },
        { key: 'revenue', label: 'Tržby dnes', value: formatCurrencyValue(this.dailyRevenue), icon: faEuroSign },
      ];
    },
    dailyRevenue() {
      return this.orders.filter((o) => o.status === 'completed').reduce((sum, order) => sum + order.total, 0);
    },
    filteredOrders() {
      return this.orders
        .filter((order) => (this.activeTab === 'all' ? true : order.type === this.activeTab))
        .filter((order) => (this.filters.status === 'all' ? true : order.status === this.filters.status))
        .filter((order) => {
          if (this.filters.day === 'all') return true;
          const orderDate = new Date(order.createdAt);
          const today = new Date();
          if (this.filters.day === 'today') {
            return orderDate.toDateString() === today.toDateString();
          }
          if (this.filters.day === 'yesterday') {
            const yesterday = new Date(today);
            yesterday.setDate(today.getDate() - 1);
            return orderDate.toDateString() === yesterday.toDateString();
          }
          if (this.filters.day === 'week') {
            const weekAgo = new Date(today);
            weekAgo.setDate(today.getDate() - 7);
            return orderDate >= weekAgo;
          }
          return true;
        })
        .filter((order) => {
          const query = this.filters.search.trim().toLowerCase();
          if (!query) return true;
          return [order.id, order.customer, order.location].some((value) =>
            String(value).toLowerCase().includes(query)
          );
        });
    },
    statusUpdateOptions() {
      return this.statusOptions.filter((option) => option.value !== 'all');
    },
    previewTitle() {
      return this.selectedOrder ? `Detail objednávky #${this.selectedOrder.id}` : 'Detail objednávky';
    },
    progressPercent() {
      if (!this.selectedOrder || !this.selectedOrder.remainingSeconds) return 0;
      const total = 1800;
      return Math.min(100, Math.max(0, (this.selectedOrder.remainingSeconds / total) * 100));
    },
    selectedCategory() {
      return (
        this.menuCategories.find((category) => category.key === this.selectedCategoryKey) || this.menuCategories[0]
      );
    },
    menuItemsForCategory() {
      return this.selectedCategory?.items || [];
    },
    variantOptions() {
      if (!this.selectedMenuItem?.variants?.length) return [];
      return this.selectedMenuItem.variants.map((variant) => ({
        label: variant.label,
        value: variant.id,
      }));
    },
    canAddItem() {
      return Boolean(this.selectedMenuItem && (this.selectedMenuItem.variants?.length ? this.selectedVariantId : true));
    },
    createFormTotal() {
      return this.createForm.items.reduce((sum, item) => sum + item.price * item.quantity, 0);
    },
    canCreateOrder() {
      return Boolean(this.createForm.table && this.createForm.items.length);
    },
  },
  methods: {
    tabClass(key) {
      return key === this.activeTab
        ? 'bg-white text-primary shadow-sm'
        : 'bg-white/40 text-deep/70 hover:bg-white hover:text-primary';
    },
    formatAmount(value) {
      return formatCurrencyValue(value);
    },
    formatTime(seconds) {
      if (!seconds || seconds < 0) return '—';
      const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
      const secs = String(seconds % 60).padStart(2, '0');
      return `${mins}:${secs}`;
    },
    formatDateTime(value) {
      return new Intl.DateTimeFormat('sk-SK', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
      }).format(new Date(value));
    },
    openOrderPreview(order) {
      this.selectedOrder = { ...order };
      this.statusUpdate = order.status;
      this.previewVisible = true;
    },
    adjustTime(seconds) {
      if (!this.selectedOrder) return;
      this.selectedOrder.remainingSeconds = Math.max(0, (this.selectedOrder.remainingSeconds || 0) + seconds);
    },
    addFiveMinutes(order) {
      const updatedSeconds = Math.max(0, (order.remainingSeconds || 0) + 300);
      order.remainingSeconds = updatedSeconds;
      if (this.selectedOrder?.id === order.id) {
        this.selectedOrder.remainingSeconds = updatedSeconds;
      }
    },
    openCreateModal() {
      this.createModalVisible = true;
    },
    closeCreateModal() {
      this.createModalVisible = false;
      this.resetCreateForm();
    },
    resetCreateForm() {
      this.createForm = {
        type: 'dineIn',
        customer: '',
        phone: '',
        table: '',
        payment: 'online',
        note: '',
        items: [],
      };
      this.selectedCategoryKey = this.menuCategories[0].key;
      this.selectedMenuItem = null;
      this.selectedVariantId = null;
      this.selectedExtras = [];
      this.selectedRemovals = [];
    },
    selectCategory(key) {
      this.selectedCategoryKey = key;
      this.selectedMenuItem = null;
      this.selectedVariantId = null;
      this.selectedExtras = [];
      this.selectedRemovals = [];
    },
    selectMenuItem(item) {
      this.selectedMenuItem = item;
      this.selectedVariantId = item.variants?.[0]?.id || null;
      this.selectedExtras = [];
      this.selectedRemovals = [];
    },
    toggleExtra(id) {
      if (this.selectedExtras.includes(id)) {
        this.selectedExtras = this.selectedExtras.filter((extra) => extra !== id);
      } else {
        this.selectedExtras = [...this.selectedExtras, id];
      }
    },
    toggleRemoval(id) {
      if (this.selectedRemovals.includes(id)) {
        this.selectedRemovals = this.selectedRemovals.filter((item) => item !== id);
      } else {
        this.selectedRemovals = [...this.selectedRemovals, id];
      }
    },
    addSelectedItemToForm() {
      if (!this.canAddItem) return;
      const basePrice = this.selectedMenuItem.price;
      const variant = this.selectedMenuItem.variants?.find((variant) => variant.id === this.selectedVariantId);
      const extras = this.selectedExtras
        .map((id) => this.selectedMenuItem.extras?.find((extra) => extra.id === id))
        .filter(Boolean);
      const removed = this.selectedRemovals
        .map((id) => this.selectedMenuItem.removables?.find((item) => item.id === id)?.label)
        .filter(Boolean);
      const extrasPrice = extras.reduce((sum, extra) => sum + extra.price, 0);
      const price = basePrice + (variant?.price || 0) + extrasPrice;
      this.createForm.items.push({
        id: `${this.selectedMenuItem.id}-${Date.now()}`,
        name: this.selectedMenuItem.name,
        variant: variant?.label || null,
        extras: extras.map((extra) => extra.label),
        removed,
        quantity: 1,
        price,
      });
      this.selectedMenuItem = null;
      this.selectedVariantId = null;
      this.selectedExtras = [];
      this.selectedRemovals = [];
    },
    removeFormItem(index) {
      this.createForm.items.splice(index, 1);
    },
    createOrder() {
      if (!this.canCreateOrder) return;
      const nextId = (Number(this.orders[0]?.id || '10000') + 1).toString();
      const newOrder = {
        id: nextId,
        customer: this.createForm.customer,
        location: this.createForm.table,
        type: this.createForm.type,
        status: 'new',
        remainingSeconds: 0,
        total: this.createFormTotal,
        createdAt: new Date().toISOString(),
        payment:
          this.paymentOptions.find((option) => option.value === this.createForm.payment)?.label || 'Online platba',
        items: this.createForm.items.map((item) => ({ ...item })),
      };
      this.orders = [newOrder, ...this.orders];
      this.closeCreateModal();
    },
    openDeleteModal(order) {
      this.selectedOrder = order;
      this.deleteModalVisible = true;
    },
    confirmDelete() {
      // TODO: implement remove action
      this.deleteModalVisible = false;
    },
  },
};
</script>

<style lang="scss" scoped>
.padding-item {
  padding: 0.7rem 0.5rem;
}
</style>
