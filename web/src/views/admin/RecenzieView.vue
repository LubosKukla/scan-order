<template>
  <section class="space-y-8">
    <AdminPageHeader
      title="Recenzie"
      subtitle="Pozrite si, čo si vaši zákazníci myslia o vašej reštaurácii a jedlách."
    />

    <div class="space-y-6 bg-ink border-transparent">
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
                <p class="text-xs font-semibold text-left text-deep">{{ card.label }}</p>
                <div class="space-y-1 f">
                  <p v-if="card.key !== 'averageRating'" class="text-md font-semibold text-deep">{{ card.value }}</p>
                  <ReviewRatingDisplay
                    v-if="card.key === 'averageRating'"
                    :rating="averageRating"
                    :show-value="true"
                  />
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
        <BaseInput v-model="filters.search" type="search" placeholder="Hľadať podľa mena, jedla alebo textu..." />
        <BaseSelect v-model="filters.rating" :options="ratingOptions" placeholder="Všetky hodnotenia" />
        <BaseSelect v-model="filters.period" :options="monthOptions" placeholder="Všetky mesiace" />
      </div>
    </BaseCard>

    <BaseCard class="space-y-4">
      <div>
        <p class="text-lg font-semibold text-deep">Zoznam recenzií</p>
        <p class="text-sm text-deep">Zobrazuje sa {{ filteredReviews.length }} z {{ reviews.length }} recenzií</p>
      </div>

      <div class="hidden xl:block overflow-x-auto">
        <table class="min-w-full divide-y divide-ink/20 text-sm">
          <thead>
            <tr class="text-left text-xs font-semibold uppercase text-deep/60">
              <th class="px-4 py-3">Typ</th>
              <th class="px-4 py-3">Recenzent</th>
              <th class="px-4 py-3">Hodnotenie</th>
              <th class="px-4 py-3">Komentár</th>
              <th class="px-4 py-3">Dátum</th>
              <th class="px-4 py-3">Zdroj</th>
              <th class="px-4 py-3 text-right">Akcie</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="review in filteredReviews"
              :key="review.id"
              class="text-deep hover:bg-ink/30 border-x border-x-white hover:border-x hover:border-x-ink/80 border-t border-t-ink"
            >
              <td class="padding-item">
                <ReviewTypeBadge :type="review.type" />
              </td>
              <td class="padding-item font-semibold">
                {{ reviewerName(review) }}
              </td>
              <td class="padding-item">
                <ReviewRatingDisplay :rating="review.rating" />
              </td>
              <td class="padding-item">
                <p>{{ reviewText(review) }}</p>
                <p v-if="reviewDishDescription(review)" class="text-xs text-deep/60">
                  {{ reviewDishDescription(review) }}
                </p>
              </td>
              <td class="padding-item text-deep/70">
                {{ formatDate(reviewDateValue(review)) }}
              </td>
              <td class="padding-item">
                <ReviewSourceTag :type="reviewSourceType(review)" :label="reviewSourceLabel(review)" />
              </td>
              <td class="padding-item text-right">
                <div class="flex items-center justify-end gap-2">
                  <BaseButton
                    variant="noBackground"
                    icon="view"
                    class="text-deep/70 hover:text-primary"
                    @click="openView(review)"
                  />
                  <BaseButton
                    variant="noBackground"
                    icon="reply"
                    :class="replyButtonClass(review)"
                    @click="openReply(review)"
                  />
                  <BaseButton
                    variant="noBackground"
                    icon="trash"
                    class="text-danger hover:text-danger"
                    @click="openDelete(review)"
                  />
                </div>
              </td>
            </tr>
            <tr v-if="!filteredReviews.length">
              <td colspan="7" class="px-4 py-8 text-center text-sm text-deep">Žiadne recenzie pre zvolené filtre.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="space-y-3 xl:hidden">
        <BaseCard
          v-for="review in filteredReviews"
          :key="`mobile-${review.id}`"
          class="border border-ink/20 shadow-sm space-y-3 p-4"
          no-padding="true"
        >
          <div class="flex md:items-center justify-between flex-col gap-2 md:flex-row items-start">
            <ReviewTypeBadge :type="review.type" />
            <ReviewSourceTag :type="reviewSourceType(review)" :label="reviewSourceLabel(review)" />
          </div>
          <div>
            <p class="font-semibold text-deep">{{ reviewerName(review) }}</p>
            <p class="text-xs text-deep/90">{{ formatDate(reviewDateValue(review)) }}</p>
          </div>
          <ReviewRatingDisplay :rating="review.rating" />
          <p class="text-sm text-deep">{{ reviewText(review) }}</p>
          <p v-if="reviewDishDescription(review)" class="text-xs text-deep/90">
            {{ reviewDishDescription(review) }}
          </p>

          <div class="flex items-center justify-end gap-2">
            <BaseButton
              variant="noBackground"
              icon="view"
              class="text-deep/70 hover:text-primary"
              @click="openView(review)"
            />
            <BaseButton
              variant="noBackground"
              icon="reply"
              :class="replyButtonClass(review)"
              @click="openReply(review)"
            />
            <BaseButton
              variant="noBackground"
              icon="trash"
              class="text-danger hover:text-danger"
              @click="openDelete(review)"
            />
          </div>
        </BaseCard>
        <p v-if="!filteredReviews.length" class="text-center text-sm text-deep/60">
          Žiadne recenzie pre zvolené filtre.
        </p>
      </div>
    </BaseCard>

    <BaseModal v-model="viewModalVisible" title="Detail recenzie">
      <div v-if="selectedReview" class="space-y-4">
        <p class="text-sm font-semibold text-deep">{{ typeLabel(selectedReview.type) }}</p>
        <div class="flex items-start justify-between gap-4">
          <div>
            <p class="text-lg font-semibold text-deep">{{ reviewerName(selectedReview) }}</p>
            <p class="text-xs text-deep/90">{{ formatDate(reviewDateValue(selectedReview)) }}</p>
          </div>
          <ReviewSourceTag :type="reviewSourceType(selectedReview)" :label="reviewSourceLabel(selectedReview)" />
        </div>
        <ReviewRatingDisplay :rating="selectedReview.rating" showValue="true" />
        <BaseCard class="bg-ink/40 border-none">
          <p class="text-sm text-deep">{{ reviewText(selectedReview) }}</p>
          <p v-if="reviewDishDescription(selectedReview)" class="text-xs text-deep/80 mt-2">
            {{ reviewDishDescription(selectedReview) }}
          </p>
        </BaseCard>
        <div v-if="reviewResponses(selectedReview).length" class="space-y-3">
          <p class="text-sm font-semibold text-deep">Odpovede</p>
          <div class="space-y-3">
            <BaseCard
              v-for="responseItem in reviewResponses(selectedReview)"
              :key="responseItem.id"
              class="bg-white border border-ink/20"
            >
              <div class="flex items-start justify-between gap-3">
                <div class="space-y-1">
                  <p class="text-xs font-semibold text-deep">{{ responseAuthor(responseItem) }}</p>
                  <p class="text-xs text-deep/70">{{ formatDate(responseDateValue(responseItem)) }}</p>
                </div>
                <BaseButton
                  variant="noBackground"
                  icon="trash"
                  class="text-danger hover:text-danger"
                  @click="openDeleteResponse(responseItem)"
                />
              </div>
              <p class="text-sm text-deep mt-2">{{ reviewText(responseItem) }}</p>
            </BaseCard>
          </div>
        </div>
      </div>
      <template #footer>
        <BaseButton variant="secondary" @click="viewModalVisible = false">Zavrieť</BaseButton>
      </template>
    </BaseModal>

    <BaseModal v-model="replyModalVisible" title="Odpoveď na recenziu">
      <div v-if="selectedReview" class="space-y-4">
        <div class="flex items-start justify-between gap-4">
          <div>
            <p class="text-sm font-semibold text-deep/70">{{ typeLabel(selectedReview.type) }}</p>
            <p class="text-lg font-semibold text-deep">{{ reviewerName(selectedReview) }}</p>
            <p class="text-xs text-deep/60">{{ formatDate(reviewDateValue(selectedReview)) }}</p>
          </div>
          <ReviewSourceTag :type="reviewSourceType(selectedReview)" :label="reviewSourceLabel(selectedReview)" />
        </div>
        <ReviewRatingDisplay :rating="selectedReview.rating" showValue="true" />
        <BaseCard class="bg-ink/40 border-none">
          <p class="text-sm text-deep">{{ reviewText(selectedReview) }}</p>
        </BaseCard>
        <div class="space-y-2">
          <BaseTextarea
            v-model="replyForm.message"
            label="Odpoveď na recenziu"
            placeholder="Napíšte vašu odpoveď zákazníkovi..."
            rows="4"
          />
        </div>
        <!--
        <BaseCard class="flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-deep">Zobraziť odpoveď verejne</p>
            <p class="text-xs text-deep/70">Odpoveď bude viditeľná pre všetkých zákazníkov</p>
          </div>
          <BaseToggle v-model="replyForm.public" />
        </BaseCard>
        -->
      </div>
      <template #footer>
        <BaseButton variant="secondary" @click="replyModalVisible = false">Zavrieť</BaseButton>
        <BaseButton icon="send" :disabled="!replyForm.message.trim()" @click="submitReply">Odoslať odpoveď</BaseButton>
      </template>
    </BaseModal>

    <BaseModal v-model="deleteModalVisible" title="Vymazať recenziu?">
      <div class="space-y-3">
        <p class="text-sm text-deep">
          Ste si istý, že chcete odstrániť túto recenziu?
          <strong v-if="deleteTarget">{{ responseAuthor(deleteTarget) }}</strong>
        </p>
        <p class="text-xs text-deep/60">Táto akcia je nevratná.</p>
      </div>
      <template #footer>
        <BaseButton variant="secondary" @click="deleteModalVisible = false">Zrušiť</BaseButton>
        <BaseButton icon="trash" @click="confirmDelete">Odstrániť</BaseButton>
      </template>
    </BaseModal>
  </section>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faStar, faCommentDots, faCalendarCheck, faChartLine } from '@fortawesome/free-solid-svg-icons';
import BaseCard from '@/components/global/containers/BaseCard.vue';
import BaseInput from '@/components/global/inputs/BaseInput.vue';
import BaseSelect from '@/components/global/inputs/BaseSelect.vue';
import BaseButton from '@/components/global/buttons/BaseButton.vue';
import BaseTextarea from '@/components/global/inputs/BaseTextarea.vue';
// import BaseToggle from '@/components/global/inputs/BaseToggle.vue';
import BaseModal from '@/components/global/containers/BaseModal.vue';
import AdminPageHeader from '@/components/layout/funkcionality/AdminPageHeader.vue';
import ReviewTypeBadge from '@/components/global/feedback/ReviewTypeBadge.vue';
import ReviewRatingDisplay from '@/components/global/feedback/ReviewRatingDisplay.vue';
import ReviewSourceTag from '@/components/global/feedback/ReviewSourceTag.vue';

const RATING_OPTIONS = [
  { label: 'Všetky hodnotenia', value: 'all' },
  { label: '5 hviezdičiek', value: '5' },
  { label: '4 hviezdičky a viac', value: '4' },
  { label: '3 hviezdičky a viac', value: '3' },
  { label: '2 a menej', value: '2_or_less' },
];
const PERIOD_OPTIONS = [
  { label: 'Všetky mesiace', value: 'all' },
  { label: 'Posledný mesiac', value: 'last_month' },
  { label: 'Posledný kvartál', value: 'last_quarter' },
  { label: 'Posledný rok', value: 'last_year' },
  { label: 'Minulý mesiac', value: 'prev_month' },
  { label: 'Minulý kvartál', value: 'prev_quarter' },
  { label: 'Minulý rok', value: 'prev_year' },
];

export default {
  name: 'AdminRecenzieView',
  components: {
    AdminPageHeader,
    BaseCard,
    BaseInput,
    BaseSelect,
    BaseButton,
    BaseTextarea,
    // BaseToggle,
    BaseModal,
    ReviewTypeBadge,
    ReviewRatingDisplay,
    ReviewSourceTag,
    FontAwesomeIcon,
  },
  data() {
    return {
      tabs: [
        { key: 'all', label: 'Všetky' },
        { key: 'restaurant', label: 'Reštaurácia' },
        { key: 'menu_item', label: 'Jedlá' },
      ],
      activeTab: 'all',
      filters: {
        search: '',
        rating: 'all',
        period: 'all',
      },
      ratingOptions: RATING_OPTIONS,
      searchTimer: null,
      selectedReview: null,
      replyModalVisible: false,
      viewModalVisible: false,
      deleteModalVisible: false,
      deleteTarget: null,
      replyForm: {
        message: '',
      },
    };
  },
  computed: {
    reviews() {
      return this.$store.getters['reviews/reviews'] || [];
    },
    stats() {
      return this.$store.getters['reviews/stats'] || {};
    },
    averageRating() {
      return Number(this.stats.average_rating) || 0;
    },
    totalReviews() {
      return Number(this.stats.total_reviews) || 0;
    },
    reviewsThisMonth() {
      return Number(this.stats.reviews_this_month) || 0;
    },
    responseRate() {
      return Number(this.stats.response_rate) || 0;
    },
    statCards() {
      return [
        { key: 'averageRating', label: 'Priemerné hodnotenie', value: this.averageRating.toFixed(1), icon: faStar },
        { key: 'total', label: 'Celkovo recenzií', value: this.totalReviews, icon: faCommentDots },
        { key: 'monthly', label: 'Tento mesiac', value: this.reviewsThisMonth, icon: faCalendarCheck },
        { key: 'response', label: 'Miera odpovedí', value: `${this.responseRate}%`, icon: faChartLine },
      ];
    },
    //generated by ai
    monthOptions() {
      return PERIOD_OPTIONS;
    },
    filteredReviews() {
      return this.reviews;
    },
  },
  methods: {
    async loadReviews() {
      try {
        await this.$store.dispatch('reviews/fetchReviews', {
          search: this.filters.search,
          rating: this.filters.rating,
          period: this.filters.period,
          type: this.activeTab,
        });
      } catch (err) {
        // chyby su osetrene v store
      }
    },
    async loadStats() {
      try {
        await this.$store.dispatch('reviews/fetchReviewStats', {
          type: this.activeTab,
        });
      } catch (err) {
        // chyby su osetrene v store
      }
    },
    scheduleLoadReviews() {
      clearTimeout(this.searchTimer);
      this.searchTimer = setTimeout(() => {
        this.loadReviews();
      }, 300);
    },
    tabClass(key) {
      return key === this.activeTab
        ? 'bg-white text-primary shadow-sm'
        : 'bg-white/40 text-deep/70 hover:bg-white hover:text-primary';
    },
    typeLabel(type) {
      return type === 'menu_item' ? 'Recenzia jedla' : 'Recenzia reštaurácie';
    },
    reviewerName(review) {
      const customer = review.customer || {};
      const name = [customer.name, customer.surname].filter(Boolean).join(' ');
      return name || 'Anonymous';
    },
    reviewText(review) {
      return review.text || '';
    },
    reviewDateValue(review) {
      return review.created_at || review.updated_at || null;
    },
    reviewDishDescription(review) {
      const menuItem = review.menu_item || review.menuItem;
      return menuItem ? 'Jedlo: ' + menuItem.name : '';
    },
    reviewSourceType() {
      return 'online';
    },
    reviewSourceLabel() {
      return 'Online';
    },
    replyButtonClass(review) {
      return this.reviewRestaurantResponse(review)
        ? 'text-primary'
        : 'text-deep/70 hover:text-primary';
    },
    reviewRestaurantResponse(review) {
      var responses = this.reviewResponses(review);
      for (var i = 0; i < responses.length; i++) {
        var response = responses[i];
        if (!response.customer_id) return response;
      }
      return null;
    },
    reviewResponses(review) {
      return review.responses || [];
    },
    responseAuthor(response) {
      if (!response.customer_id) return 'Reštaurácia';
      return this.reviewerName(response);
    },
    responseDateValue(response) {
      return response.created_at || response.updated_at || null;
    },
    formatDate(dateValue) {
      if (!dateValue) return '';
      return new Intl.DateTimeFormat('sk-SK', {
        day: 'numeric',
        month: 'numeric',
        year: 'numeric',
      }).format(new Date(dateValue));
    },
    openView(review) {
      this.selectedReview = review;
      this.viewModalVisible = true;
    },
    openReply(review) {
      this.selectedReview = review;
      var response = this.reviewRestaurantResponse(review);
      this.replyForm = { message: response ? response.text : '' };
      this.replyModalVisible = true;
    },
    openDelete(review) {
      this.deleteTarget = review;
      this.deleteModalVisible = true;
    },
    openDeleteResponse(response) {
      this.deleteTarget = response;
      this.deleteModalVisible = true;
    },
    async submitReply() {
      if (!this.replyForm.message.trim() || !this.selectedReview) return;
      var response = this.reviewRestaurantResponse(this.selectedReview);
      var ok = response
        ? await this.$store.dispatch('reviews/updateResponse', { reviewId: response.id, text: this.replyForm.message })
        : await this.$store.dispatch('reviews/respondReview', {
            reviewId: this.selectedReview.id,
            text: this.replyForm.message,
          });
      if (!ok) return;
      this.replyModalVisible = false;
      await this.loadReviews();
      await this.loadStats();
    },
    async confirmDelete() {
      if (!this.deleteTarget) return;
      var ok = await this.$store.dispatch('reviews/deleteReview', this.deleteTarget.id);
      if (!ok) return;
      if (this.selectedReview && this.deleteTarget.id === this.selectedReview.id) {
        this.viewModalVisible = false;
        this.selectedReview = null;
      }
      this.deleteTarget = null;
      this.deleteModalVisible = false;
      await this.loadReviews();
      await this.loadStats();
      this.viewModalVisible = false;
    },
  },
  created() {
    this.loadReviews();
    this.loadStats();
  },
  watch: {
    'filters.search': function () {
      this.scheduleLoadReviews();
    },
    'filters.rating': function () {
      this.loadReviews();
      this.loadStats();
    },
    'filters.period': function () {
      this.loadReviews();
      this.loadStats();
    },
    activeTab: function () {
      this.loadReviews();
      this.loadStats();
    },
  },
};
</script>

<style lang="scss" scoped>
.padding-item {
  padding: 0.7rem 0.5rem;
}
</style>
