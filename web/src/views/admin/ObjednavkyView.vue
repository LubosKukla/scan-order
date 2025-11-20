<template>
  <section class="space-y-8">
    <AdminPageHeader title="Objednávky" subtitle="Spravujte a sledujte všetky objednávky reštaurácie v reálnom čase.">
      <template #actions>
        <BaseButton icon="add">Pridať objednávku</BaseButton>
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
                <div class="flex items-center gap-2 text-sm">
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
                  <BaseButton variant="noBackground" icon="close" class="text-danger hover:text-danger" />
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
          <div class="flex items-center gap-2">
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
            <BaseButton variant="noBackground" icon="close" class="text-danger hover:text-danger" />
          </div>
        </BaseCard>
        <p v-if="!filteredOrders.length" class="text-center text-sm text-deep/60">
          Žiadne objednávky pre zvolené filtre.
        </p>
      </div>
    </BaseCard>

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
          <div class="flex flex-col gap-1">
            <p class="text-sm font-semibold text-deep/80">Zákazník</p>
            <p class="text-lg font-semibold text-deep">{{ selectedOrder.customer }}</p>
            <p class="text-xs text-deep/70">{{ selectedOrder.location }}</p>
          </div>
          <div>
            <p class="text-sm font-semibold text-deep/80">Platba</p>
            <p class="text-lg font-semibold text-deep">{{ selectedOrder.payment }}</p>
          </div>
          <div>
            <p class="text-sm font-semibold text-deep/80">Typ objednávky</p>
            <OrderTypeBadge :type="selectedOrder.type" />
          </div>
          <div>
            <p class="text-sm font-semibold text-deep/80">Aktuálny stav</p>
            <OrderStatusBadge :status="selectedOrder.status" />
          </div>
        </div>

        <div>
          <p class="text-sm font-semibold text-deep mb-2">Objednané položky</p>
          <BaseCard class="bg-ink space-y-2">
            <div class="grid grid-cols-3 text-xs font-semibold text-deep/60">
              <p>Položka</p>
              <p class="text-center">Množstvo</p>
              <p class="text-right">Cena</p>
            </div>
            <div
              v-for="item in selectedOrder.items"
              :key="`${item.name}-${item.variant}`"
              class="grid grid-cols-3 items-center border-t border-ink/40 py-2 text-sm text-deep"
            >
              <div>
                <p class="font-semibold">{{ item.name }}</p>
                <p v-if="item.variant" class="text-xs text-deep/60">Varianta: {{ item.variant }}</p>
              </div>
              <p class="text-center">{{ item.quantity }}x</p>
              <p class="text-right">{{ formatAmount(item.price * item.quantity) }}</p>
            </div>
            <div class="grid grid-cols-3 items-center border-t border-ink/40 pt-3 text-sm font-semibold text-deep">
              <p>Celková suma</p>
              <p></p>
              <p class="text-right">{{ formatAmount(selectedOrder.total) }}</p>
            </div>
          </BaseCard>
        </div>

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
  </section>
</template>

<script>
//generated by AI
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faBell, faBolt, faCircleCheck, faEuroSign, faClock } from '@fortawesome/free-solid-svg-icons';
import AdminPageHeader from '@/components/layout/funkcionality/AdminPageHeader.vue';
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseInput from '@/components/global/inputs/BaseInput.vue';
import BaseSelect from '@/components/global/inputs/BaseSelect.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';
import OrderTypeBadge from '@/components/global/orders/OrderTypeBadge.vue';
import OrderStatusBadge from '@/components/global/orders/OrderStatusBadge.vue';
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
    BaseButton,
    BaseModal,
    OrderTypeBadge,
    OrderStatusBadge,
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
  },
};
</script>

<style lang="scss" scoped>
.padding-item {
  padding: 0.7rem 0.5rem;
}
</style>
