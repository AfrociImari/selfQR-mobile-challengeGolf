<template>
    <v-card>
        <v-toolbar color="warning" dark dense flat>
            <v-toolbar-title class="text-body-2">
                QR Code エラー
            </v-toolbar-title>
        </v-toolbar>
        <v-card-text>
            <p>退席されました。
            現在のQRCodeはご利用できません。
            </p>
           <p style="color: red; margin-top: 16px;"> 再度チェックインするためには、
            窓口にお越しください。</p>
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
        </v-card-actions>
    </v-card>
    <script>
        // 数秒後に自動でタブを閉じる
        setTimeout(() => {
            window.close();
        }, 2000);
    </script>
</template>
