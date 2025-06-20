<template>
    <MobileAppLayout :admissionDataParam="admissionData">

        <v-container class="scale-down">
            <v-row class="flex-wrap">
                <v-col cols="8" class="date-text text-body-3 text-sm-caption">日付:{{ getTodayInJapaneseFormat() }}</v-col>
                <v-col cols="3" class="boxname-text" style=" border: 2px solid red; background-color: #219640;text-align:center;">
                    {{admissionData.box_name}}</v-col>   
            </v-row>
            <v-row class="flex-wrap mt-1">
                <v-col cols="6" class="title-text text-body-2 text-sm-caption"><p>入場時刻 : {{initialTime}}</p></v-col>
                <v-col cols="6" class="title-text text-body-2 text-sm-caption"><p>現在時刻 : {{currentTime}}</p></v-col>   
            </v-row>

            <v-row class="flex-wrap mt-1">
                <v-col cols="12" class="title-text text-sm-caption">
                    <p style="font-family: monospace; min-width: 100px; display: inline-block;">経過時刻 :</p>

                    <p
                        v-if="isCountdownActive"
                        style="font-family: monospace; min-width: 100px; display: inline-block;"
                        class="bg-red lighten-5 red--text text--darken-3"
                    >
                        {{ countdownTimeFormatted }}
                    </p>

                    <p
                        v-else
                        style="font-family: monospace; min-width: 100px; display: inline-block;"
                    >
                        {{ currentUsedHHMMSS }}
                    </p>
                </v-col>
            </v-row>

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
      <v-dialog v-model="showQrCodeModal" scrollable max-width="200px" persistent>
                <QrcodeVue :value="qrValue" :size="200" level="H" />
        <v-btn color="red lighten-2" minWidth="200px" @click="showQrCodeModal = false">
                    キャンセル
                </v-btn>
    </v-dialog>
</template>

<script>
import MobileAppLayout from '@/Pages/MobileApp/MobileAppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import QrcodeVue from 'qrcode.vue';

export default {
    components: {
        MobileAppLayout,
        QrcodeVue,
    },
    setup() {
        const page = usePage();
        const admissionData = computed(() => page.props.admissionData);

        return {
            page,
            admissionData,
        };
    },

    data: () => ({
        initialUsedPrice: 0,
        initialTime: '00:00:00', // Will be set from admissionData
        boxName: '',            // Will be set from admissionData
        currentTime: '',
        currentUsedHHMMSS: '', // Elapsed time shown after countdown
        currentUsedMinutes: 0, // Minutes used for billing (adjusted for grace period)
        currentAmount: 0,
        // Price points (will be calculated)
        used10min: 0, used20min: 0, used30min: 0, used40min: 0, used50min: 0,
        used1hr: 0, used1hr30min: 0, used2hr: 0, used2hr30min: 0,
        used3hr: 0, used3hr30min: 0, used4hr: 0,
        qrValue: '',
        showQrCodeModal: false,

        // Countdown related data (no localStorage for these now)
        isCountdownActive: false,
        countdownTimeFormatted: '00:05:00', // Displays remaining countdown time
        totalElapsedSecondsFromAdmission: 0, // New property to store total time passed
    }),

    mounted() {
        this.initialTime = this.admissionData.admission_time;
        this.boxName = this.admissionData.box_name;

        // Start the main time update timer
        this.updateTime(); // Call once immediately to set initial times
        this.timer = setInterval(this.updateTime, 1000); // Update every second

        // Start polling for admission status
        this.polling = setInterval(() => {
            this.getAdmissionStatus();
        }, 3000);
    },

    beforeUnmount() {
        clearInterval(this.polling);
        clearInterval(this.timer);
        // No countdownTimer to clear directly as it's part of the main updateTime loop now
    },

    methods: {
        async getAdmissionStatus() {
            try {
                const res = await axios.get(`/admission/${this.admissionData.id}`);
                if (res.data.delete_flg == '1') {
                    location.reload();
                }
            } catch (error) {
                console.error("Error fetching admission status:", error);
            }
        },

        getTodayInJapaneseFormat() {
            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
            const weekday = daysOfWeek[date.getDay()];
            return `${year}-${month}-${day}(${weekday})`;
        },

        updateTime() {
            const now = new Date();
            this.currentTime = now.toTimeString().slice(0, 8); // "HH:MM:SS"
            this.calculateTimesAndAmounts(); // Unified function for all calculations
        },

        calculateTimesAndAmounts() {
            const [initHour, initMinute, initSecond = 0] = this.initialTime.split(':').map(Number);
            const [currHour, currMinute, currSecond] = this.currentTime.split(':').map(Number);

            const initialDate = new Date();
            initialDate.setHours(initHour, initMinute, initSecond);
            const currentDate = new Date();
            currentDate.setHours(currHour, currMinute, currSecond);

            let diffMs = currentDate - initialDate;
            if (diffMs < 0) diffMs = 0; // Ensures time doesn't go negative

            // Store the total elapsed seconds from admission for internal use
            this.totalElapsedSecondsFromAdmission = Math.floor(diffMs / 1000);
            const fiveMinutesInSeconds = 300; // 5 * 60 seconds

            // --- Determine if countdown is active or regular elapsed time ---
            if (this.totalElapsedSecondsFromAdmission < fiveMinutesInSeconds) {
                this.isCountdownActive = true;
                const remainingSeconds = fiveMinutesInSeconds - this.totalElapsedSecondsFromAdmission;
                const hh = String(Math.floor(remainingSeconds / 3600)).padStart(2, '0');
                const mm = String(Math.floor((remainingSeconds % 3600) / 60)).padStart(2, '0');
                const ss = String(remainingSeconds % 60).padStart(2, '0');
                this.countdownTimeFormatted = `${hh}:${mm}:${ss}`;

                // During countdown, billable minutes are 0
                this.currentUsedMinutes = 0;
                this.currentUsedHHMMSS = `00:00:00`; // Still set this for when v-else takes over
            } else {
                this.isCountdownActive = false; // Countdown is over

                // Calculate elapsed time AFTER the 5-minute grace period
                let adjustedSeconds = this.totalElapsedSecondsFromAdmission - fiveMinutesInSeconds;
                if (adjustedSeconds < 0) adjustedSeconds = 0; // Should be handled by initial if, but for safety

                const hh = String(Math.floor(adjustedSeconds / 3600)).padStart(2, '0');
                const mm = String(Math.floor((adjustedSeconds % 3600) / 60)).padStart(2, '0');
                const ss = String(adjustedSeconds % 60).padStart(2, '0');
                this.currentUsedHHMMSS = `${hh}:${mm}:${ss}`;
                this.currentUsedMinutes = Math.floor(adjustedSeconds / 60);
            }

            // --- Calculate amount (always based on currentUsedMinutes) ---
            this.calculateUsedAmount();
        },

        calculateUsedAmount() {
            // Determine initial price based on customer class
            this.initialUsedPrice = this.admissionData.customer_class === 'g' ? 900 : 400;

            // Set all fixed price points
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

            // Calculate current amount based on currentUsedMinutes (which is 0 during grace period)
            const minutesForBilling = this.currentUsedMinutes;
            let extraCost = 0;

            if (minutesForBilling <= 60) {
                // Add ¥100 for every 10-minute block after the initial 0-10 min block
                const tenMinBlocks = Math.ceil(minutesForBilling / 10);
                extraCost = (tenMinBlocks - 1) * 100;
                if (extraCost < 0) extraCost = 0; // Ensures no negative cost for 0-10 min
            } else {
                // After 60 minutes, the initial 1-hour cost is already accounted for
                extraCost = 500;

                // Add ¥100 for every 30-minute block after the first hour
                const additionalMinutes = minutesForBilling - 60;
                const thirtyMinBlocks = Math.ceil(additionalMinutes / 30);
                extraCost += thirtyMinBlocks * 100;
            }

            this.currentAmount = this.initialUsedPrice + extraCost;
        },

        getColColor(minStart, minEnd) {
            // During countdown, no pricing blocks are highlighted
            if (this.isCountdownActive) {
                return '';
            }
            // After countdown, highlight based on adjusted minutes
            const minutes = this.currentUsedMinutes;
            if (minutes >= minStart && minutes < minEnd) {
                return 'bg-orange'; // Current active block
            }
            if (minutes >= minEnd) {
                return 'bg-grey-lighten-1'; // Already passed block
            }
            return ''; // Future blocks
        },

         showQRCode() {
            this.qrValue = this.admissionData.admission_day+" "+this.admissionData.admission_time+" "+this.currentTime+" "+this.admissionData.box_name+" "+this.admissionData.customer_id+" "+this.admissionData.customer_class+" "+this.admissionData.holiday_flg+" "+this.admissionData.junior_flg;
            this.showQrCodeModal = true;
        },
    },
};
</script>

<style>
/* Your existing styles remain the same */
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
.boxname-text {
    text-align: left;
    font-size: 28px;
    color: rgb(230, 237, 232);
    font-weight: bold;
}

.bordered-col {
    border: 1px solid #ccc;
    text-align: center;
    padding: 2px;
}

</style>