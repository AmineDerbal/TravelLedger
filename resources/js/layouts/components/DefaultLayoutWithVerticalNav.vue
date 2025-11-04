<script setup>
import navItems from '@/navigation/vertical';
import { themeConfig } from '@themeConfig';

// Components
import Footer from '@/layouts/components/Footer.vue';
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue';
import UserProfile from '@/layouts/components/UserProfile.vue';
import NavBarI18n from '@core/components/I18n.vue';

// @layouts plugin
import { VerticalNavLayout } from '@layouts';

// stores
import useLedgerStore from '@/store/ledgerStore';

const ledgerItem = {
  color: 'success',
  icon: 'tabler-currency-dollar',
};

const ledgerStore = useLedgerStore();
const ledger = computed(() => ledgerStore.ledger);
const ledgerOptions = computed(() => ledgerStore.ledgersForSelect);

const switchLedger = async (id) => {
  if (id === ledger.value.id) return;

  await ledgerStore.getLedger(id);
};

onBeforeMount(async () => {
  if (!ledger.value.name || !ledger.value.id || !ledger.value.balance) {
    await ledgerStore.getFirstLedger();
    return;
  }
  await ledgerStore.UpdateLedgerBalance(ledgerStore.ledger.id);
  await ledgerStore.getLedgersForSelect();
});
</script>

<template>
  <VerticalNavLayout :nav-items="navItems">
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <IconBtn
          id="vertical-nav-toggle-btn"
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon
            size="26"
            icon="tabler-menu-2"
          />
        </IconBtn>

        <NavbarThemeSwitcher />

        <VSpacer />

        <NavBarI18n
          v-if="
            themeConfig.app.i18n.enable &&
            themeConfig.app.i18n.langConfig?.length
          "
          :languages="themeConfig.app.i18n.langConfig"
        />
        <VSpacer />

        <VCardText>
          <VRow>
            <VCol
              cols="6"
              md="3"
            >
              <div class="d-flex gap-x-4 align-center cursor-pointer">
                <div
                  class="d-flex align-center"
                  style="cursor: pointer"
                >
                  <VAvatar
                    :color="ledgerItem.color"
                    variant="tonal"
                    size="40"
                    rounded
                  >
                    <VIcon :icon="ledgerItem.icon" />
                  </VAvatar>
                  <div
                    class="d-flex flex-column ms-3"
                    :key="ledger.balance"
                  >
                    <h5 class="text-h5">{{ ledger.name }}</h5>
                    <div class="text-body-2 d-inline-flex align-center">
                      {{ ledger.balance }}
                      <span class="ms-1">DZD</span>
                    </div>
                  </div>
                </div>

                <VMenu
                  activator="parent"
                  width="230"
                  location="bottom end"
                  offset="10px"
                >
                  <VList>
                    <VListItem
                      v-for="item in ledgerOptions"
                      class="cursor-pointer"
                      :key="item.id"
                      @click="switchLedger(item.id)"
                      link
                    >
                      <VListItemTitle>{{ item.name }}</VListItemTitle>
                    </VListItem>
                  </VList>
                </VMenu>
              </div>
            </VCol>
          </VRow>
        </VCardText>

        <UserProfile />
      </div>
    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>

    <!-- 👉 Customizer -->
    <!-- <TheCustomizer /> -->
  </VerticalNavLayout>
</template>
