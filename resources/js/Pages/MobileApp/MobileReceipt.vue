<!-- eslint-disable prettier/prettier -->
<template>
    <MobileAppLayout :checkInParam="checkIn">

        <v-container class="scale-down">
            <v-row class="flex-wrap">
                <v-col cols="8" class="date-text text-body-3 text-sm-caption">日付:2025-05-13(火)</v-col>
                <v-col cols="4" class="date-text text-body-6 text-sm-caption">打席:30</v-col>   
            </v-row>
            <v-row class="flex-wrap mt-1">
                <v-col cols="6" class="title-text text-body-2 text-sm-caption"><p>入場時刻 : {{initialTime}}</p></v-col>
                <v-col cols="6" class="title-text text-body-2 text-sm-caption"><p>現在時刻 : {{currentTime}}</p></v-col>   
            </v-row>
            <v-row class="flex-wrap mt-1">
                <v-col cols="12" class="title-text text-sm-caption">
                    <p style="font-family: monospace; min-width: 100px; display: inline-block;">経過時刻 :</p>
                    <p style="font-family: monospace; min-width: 100px; display: inline-block;" 
                    :class="{ 'bg-red lighten-5 red--text text--darken-3': isCountdownActive }">{{currentUsedHHMMSS}}</p></v-col>  
            </v-row>
              <!-- <p class="title-text">入場時刻 : {{currentUsedHHMMSS}}</p> -->
            <v-row class="flex-wrap mt-1">
                <v-col cols="2" class="bordered-col text-body-2 text-sm-caption">10分</v-col>
                <v-col cols="2" class="bordered-col text-body-2 text-sm-caption">20分</v-col>
                <v-col cols="2" class="bordered-col text-body-2 text-sm-caption">30分</v-col>
                <v-col cols="2" class="bordered-col text-body-2 text-sm-caption">40分</v-col>
                <v-col cols="2" class="bordered-col text-body-2 text-sm-caption">50分</v-col>
                <v-col cols="2" class="bordered-col text-body-2 text-sm-caption">1時間</v-col>
            </v-row>
            <v-row class="flex-wrap">
                <v-col cols="2" :class="['bordered-col', getColColor(0,10), 'text-body-2', 'text-sm-caption']">￥{{ used10min }}</v-col>
                <v-col cols="2" :class="['bordered-col', getColColor(10,20), 'text-body-2', 'text-sm-caption']">￥{{ used20min }}</v-col>
                <v-col cols="2" :class="['bordered-col', getColColor(20,30), 'text-body-2', 'text-sm-caption']">￥{{ used30min }}</v-col>
                <v-col cols="2" :class="['bordered-col', getColColor(30,40), 'text-body-2', 'text-sm-caption']">￥{{ used40min }}</v-col>
                <v-col cols="2" :class="['bordered-col', getColColor(40,50), 'text-body-2', 'text-sm-caption']">￥{{ used50min }}</v-col>
                <v-col cols="2" :class="['bordered-col', getColColor(50,60), 'text-body-2', 'text-sm-caption']">￥{{ used1hr }}</v-col>
            </v-row>

            <v-row class="mt-5 flex-wrap">
                <v-col cols="6" class="bordered-col">1時間30分</v-col>
                <v-col cols="6" class="bordered-col">2時間</v-col>
            </v-row>
            <v-row class="flex-wrap">
                <v-col cols="6" :class="['bordered-col', getColColor(60,90)]">￥{{used1hr30min}}</v-col>
                <v-col cols="6" :class="['bordered-col', getColColor(90,120)]">￥{{used2hr}}</v-col>
            </v-row>

            <v-row class="mt-5 flex-wrap">
                <v-col cols="6" class="bordered-col">2時間30分</v-col>
                <v-col cols="6" class="bordered-col">3時間</v-col>
            </v-row>
            <v-row>
                <v-col cols="6" :class="['bordered-col', getColColor(120,150)]">￥{{used2hr30min}}</v-col>
                <v-col cols="6" :class="['bordered-col', getColColor(150,180)]">￥{{used3hr}}</v-col>
            </v-row>

            <v-row class="mt-5 flex-wrap">
                <v-col cols="6" class="bordered-col">3時間30分</v-col>
                <v-col cols="6" class="bordered-col">4時間</v-col>
            </v-row>
            <v-row class="mb-2 flex-wrap">
                <v-col cols="6" :class="['bordered-col', getColColor(180,210)]">￥{{used3hr30min}}</v-col>
                <v-col cols="6" :class="['bordered-col', getColColor(210,240)]">￥{{used4hr}}</v-col>
            </v-row>

            <v-row class="mb-2 flex-wrap">
                <p>以降&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;30分毎に+100円</p>
            </v-row>

            <p class="title-text">現在の料金 : <font style="color: darkorange;">￥{{currentAmount}}</font></p>

            <v-row class="justify-center py-1 flex-wrap">
                <v-btn color="blue lighten-2" variant="outlined" text
                    style="height: 50px;width: 150px; font-size: 20px; padding: 0 12px;" @click="showQRCode">
                    退席
                </v-btn>
            </v-row>

        </v-container>
    </MobileAppLayout>
     <!-- Modal for QR Code -->
    <v-dialog v-model="showQrCodeModal" scrollable max-width="200px" persistent>
            <QrcodeVue :value="qrValue" :size="200" level="H" />
        <v-btn color="red lighten-2" minWidth="200px" @click="showQrCodeModal = false">
                    キャンセル
                </v-btn>
    <!-- </div> -->
    </v-dialog>
</template>

<script>
import MobileAppLayout from '@/Pages/MobileApp/MobileAppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import eventBus from '@/Event/eventBus';
import QrcodeVue from 'qrcode.vue';

export default {
    components: {
        MobileAppLayout,
        QrcodeVue,
    },
    setup() {
        const page = usePage();
        const checkIn = computed(() => page.props.checkin);

        return {
            page,
            checkIn,
        };
    },

    data: () => ({
        initialUsedPrice: 0,
        initialTime: '13:05:00',
        delayTime: '5',
        currentTime: '',
        currentUsedHHMMSS:'',
        currentUsedMinutes: 0,
        currentAmount:0,
        used10min: 0,
        used20min: 0,
        used30min: 0,
        used40min: 0,
        used50min: 0,
        used1hr: 0,
        usedihr30min: 0,
        used2hr: 0,
        used2hr30min: 0,
        used3hr: 0,
        used3hr30min: 0,
        used4hr: 0,
        qrValue: '',
        showQrCodeModal: false,
        isCountdownActive: false,
        countdownRemaining: 300, // 5 minutes in seconds
        countdownDone: false,
    }),

    mounted() {
        this.polling = setInterval(() => {
            //this.getCheckinStatus();
        }, 3000);

        const storedInitial = localStorage.getItem('initialTime');
        const storedCountdown = localStorage.getItem('countdownRemaining');
        const storedCountdownDone = localStorage.getItem('countdownDone');

        if (storedInitial) {
            this.initialTime = storedInitial;
        } else {
            // const now = new Date();
            // const hh = String(now.getHours()).padStart(2, '0');
            // const mm = String(now.getMinutes()).padStart(2, '0');
            // this.initialTime = `${hh}:${mm}`;
            localStorage.setItem('initialTime', this.initialTime);
        }

        if (storedCountdown) {
            this.countdownRemaining = parseInt(storedCountdown);
            this.isCountdownActive = this.countdownRemaining > 0;
        }

        if (storedCountdownDone === 'true') {
            this.countdownDone = true;
        }


        this.updateTime();
        this.timer = setInterval(this.updateTime, 1000);
    },

    beforeUnmount() {
        clearInterval(this.polling);
        clearInterval(this.timer);
    },

    methods: {
        // async getCheckinStatus() {
        //     try {
        //         const res = await axios.get(`/checkin/${this.checkIn.checkin_id}`);
        //         if (res.data.order_stop == 'true') {
        //             location.reload();
        //         }
        //     } catch (error) {
        //         console.error(error);
        //     }
        // },
        updateTime() {
            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            const ss = String(now.getSeconds()).padStart(2, '0');
            this.currentTime = `${hh}:${mm}:${ss}`;

            this.calculateUsedMinutes();
            this.calculateUsedAmount();
        },

        calculateUsedMinutes() {
            const [initHour, initMinute] = this.initialTime.split(':').map(Number);
            const [currHour, currMinute, currSecond] = this.currentTime.split(':').map(Number);

            const initialDate = new Date();
            initialDate.setHours(initHour, initMinute, 0);

            const currentDate = new Date();
            currentDate.setHours(currHour, currMinute, currSecond);

            // Calculate raw difference in milliseconds
            let diffMs = currentDate - initialDate;
            if (diffMs < 0) {
                diffMs = 0; // Handle overnight or invalid cases
            }

            // If countdown should be active
            if (!this.countdownDone && this.currentUsedMinutes <= 5 && !this.isCountdownActive) {
                this.isCountdownActive = true;
                this.countdownRemaining = 300; // 5 minutes in seconds
                localStorage.setItem('countdownRemaining', this.countdownRemaining.toString());
            }

            // Countdown active mode
            if (this.isCountdownActive) {
                if (this.countdownRemaining > 0) {
                    this.countdownRemaining--;
                    localStorage.setItem('countdownRemaining', this.countdownRemaining.toString());

                    const hh = String(Math.floor(this.countdownRemaining / 3600)).padStart(2, '0');
                    const mm = String(Math.floor((this.countdownRemaining % 3600) / 60)).padStart(2, '0');
                    const ss = String(this.countdownRemaining % 60).padStart(2, '0');
                    this.currentUsedHHMMSS = `${hh}:${mm}:${ss}`;
                } else {
                    this.isCountdownActive = false;
                    this.countdownDone = true;
                    localStorage.setItem('countdownDone', 'true');
                }

                // Keep minutes display paused at 0~5 min
                this.currentUsedMinutes = Math.floor(diffMs / 60000);
                return;
            }

            // Normal counting mode (after countdown is done)
            let adjustedDiffMs = diffMs;
            if (this.countdownDone) {
                adjustedDiffMs -= 5 * 60 * 1000; // subtract 5 minutes in milliseconds
                if (adjustedDiffMs < 0) adjustedDiffMs = 0;
            }

            this.currentUsedMinutes = Math.floor(adjustedDiffMs / 60000);

            const diffSeconds = Math.floor(adjustedDiffMs / 1000);
            const hh = String(Math.floor(diffSeconds / 3600)).padStart(2, '0');
            const mm = String(Math.floor((diffSeconds % 3600) / 60)).padStart(2, '0');
            const ss = String(diffSeconds % 60).padStart(2, '0');
            this.currentUsedHHMMSS = `${hh}:${mm}:${ss}`;
        },

        //金額を計算する
        calculateUsedAmount() {
            // if(this.checkIn.type == 'g'){
            //     this.initialUsedPrice = 900;
            // } else {
            //     this.initialUsedPrice = 400;
            // }
            this.used10min = this.initialUsedPrice;
            this.used20min = this.initialUsedPrice + 100;
            this.used30min = this.initialUsedPrice + 200;
            this.used40min = this.initialUsedPrice + 300;
            this.used50min = this.initialUsedPrice + 400;
            this.used1hr = this.initialUsedPrice + 500;
            this.used1hr30min = this.initialUsedPrice + 600;
            this.used2hr = this.initialUsedPrice + 700;
            this.used2hr30min = this.initialUsedPrice + 800;
            this.used3hr = this.initialUsedPrice + 900;
            this.used3hr30min = this.initialUsedPrice + 1000;
            this.used4hr = this.initialUsedPrice + 1100;

            const minutes = this.currentUsedMinutes;

            let extra = 0;

            if (minutes <= 60) {
                // Add ¥100 every 10 minutes for the first hour
                const tenMinBlocks = Math.ceil(minutes / 10);
                extra = (tenMinBlocks - 1) * 100; // First 10 minutes included in base
            } else {
                // First hour fixed
                extra = 500;

                // Additional ¥100 every 30 minutes after the first hour
                const extraMinutes = minutes - 60;
                const thirtyMinBlocks = Math.ceil(extraMinutes / 30);
                extra += thirtyMinBlocks * 100;
            }

            this.currentAmount = this.initialUsedPrice + extra;

        },
        getColColor(prev,current) {
            if (this.currentUsedMinutes >prev && this.currentUsedMinutes <= current) {
                return 'bg-orange'; // active time
            }
            if (this.currentUsedMinutes >prev && this.currentUsedMinutes > current) {
                return 'bg-grey-lighten-1'; // inactive time
            }
        },

        showQRCode() {
            this.qrValue = '2025-05-16 09:07';
            this.showQrCodeModal = true;
        },
    },
};
</script>

<style>
.title-text {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    color: rgb(16, 111, 24);
    margin-bottom: 16px;
}

.date-text {
    text-align: left;
    font-size: 18px;
    color: rgb(10, 121, 38);
    font-weight: bold;
}

.bordered-col {
    border: 1px solid #ccc;
    text-align: center;
    padding: 2px;
}

</style>
