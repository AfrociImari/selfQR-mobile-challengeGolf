<!-- eslint-disable prettier/prettier -->
<template>
  <v-app>
    <!-- Fixed Toolbar -->
    <v-app-bar color="green" dark flat app>
      <v-toolbar-title style="font-weight: bold;">チャレンジゴルフ</v-toolbar-title>

      <v-btn icon @click="showHistoryModal = true">
        <v-icon>mdi-history</v-icon>
      </v-btn>

      <v-menu offset-y>
        <template #activator="{ props }">
          <v-btn icon v-bind="props">
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item v-for="item in settingItems" :key="item.title" @click="handleSettingAction(item.action)">
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <!-- Page Content -->
    <v-main>
      <v-container fluid class="pt-4">
        <v-alert v-if="emptyOrderAlert" type="warning" class="mt-4">
          注文カートは空です！
        </v-alert>

        <!-- Modals -->

        <slot />

      </v-container>
    </v-main>

    <!-- Fixed Footer (must come after FAB) -->
    <v-footer app padless>
      <v-col class="text-center" cols="12">
        {{ new Date().getFullYear() }} — <strong>Afroci Co.Ltd</strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
import eventBus from '@/Event/eventBus';

export default {
    components: {
    },
    props: {
        checkInParam: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            showHistoryModal: false,
            orderCartData: [],
            //orderCartCount: 0,
            emptyOrderAlert: false,
            settingItems: [
                { title: 'コースメニュー', action: 'goToCourseMenu' },
                { title: '再読み込み', action: 'reloadPage' },
                { title: 'ヘルプ', action: 'openHelp' },
                // { title: 'ログアウト', action: 'logout' },
            ],
        };
    },

    computed: {
        orderCartCount() {
            if (Array.isArray(this.orderCartData)) {
                return this.orderCartData.reduce(
                    (total, item) => total + (item.quantity || 0),
                    0,
                );
            }
            return 0;
        },
    },
    mounted() {
        //this.getOrderCart();
        //eventBus.on('cart-updated', this.getOrderCart);
        //this.polling = setInterval(this.getOrderCart, 2000); // Every 3s
    },

    beforeUnmount() {
        //eventBus.off('cart-updated', this.getOrderCart);
        //clearInterval(this.polling);
    },
    methods: {
        resetTimer() {
            localStorage.removeItem('initialTime');
            localStorage.removeItem('countdownRemaining');
            localStorage.removeItem('countdownDone');
            location.reload(); // or reinitialize component data
        },

        // async getOrderCart() {
        //     // Fetch the order cart data
        //     try {
        //         const res = await axios.get(
        //             `/orderCart/${this.checkInParam.checkin_id}`,
        //         );
        //         this.orderCartData = res.data;
        //         //this.orderCartCount = this.orderCartData.length;
        //     } catch (error) {
        //         console.error(error);
        //     }
        // },

        handleSettingAction(action) {
            if (typeof this[action] === 'function') {
                this[action]();
            } else {
                console.warn(`No method defined for action: ${action}`);
            }
        },

        reloadPage() {
            localStorage.removeItem('initialTime');
            localStorage.removeItem('countdownRemaining');
            localStorage.removeItem('countdownDone');
            location.reload();
        },

        logout() {
            axios.post('/logout').then(() => {
                location.href = '/login';
            });
        },

        openHelp() {
            alert('ヘルプページは準備中です！');
        },
       
    },
};
</script>

<style>
.pdf-content {
    padding: 20px;
    border: 1px solid #ddd;
    background: white;
}
</style>
