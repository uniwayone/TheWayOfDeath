<template>
    <v-container class="ma-0 pa-0" v-if="$isMobile()">
        <v-row class="ma-0 ">
            <v-col cols="12">
                <v-text-field
                    solo
                    v-model="oldPassword"
                    label="请输入旧密码"
                    prepend-inner-icon="mdi-key-chain-variant"
                    :append-icon="showOldPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showOldPassword ? 'text' : 'password'"
                    @click:append="showOldPassword = !showOldPassword"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row class="ma-0">
            <v-col cols="12">
                <v-text-field
                    solo
                    v-model="newPassword"
                    label="请输入新密码"
                    hint="密码长度8-32位，且必须包含大小写字母/数字/符号任意三者组合，如：Ab123567、Aa@1234+"
                    counter
                    prepend-inner-icon="mdi-key-chain-variant"
                    :append-icon="showNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showNewPassword ? 'text' : 'password'"
                    @click:append="showNewPassword = !showNewPassword"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row class="ma-0">
            <v-col cols="12">
                <v-text-field
                    solo
                    v-model="newPasswordConfirm"
                    label="请再次输入新密码"
                    prepend-inner-icon="mdi-key-chain-variant"
                    :append-icon="showNewPasswordConfirm ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showNewPasswordConfirm ? 'text' : 'password'"
                    @click:append="showNewPasswordConfirm = !showNewPasswordConfirm"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row class="ma-0">
            <v-col cols="12">
                <v-btn color="#feb31a" dark class="ml-auto" @click="updatePassword" :loading="isUpdatingPassword">  
                    修改密码
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
    <v-container class="ma-0 pa-0" v-else>
        <v-row class="ma-0 ">
            <v-col cols="12" sm="8" md="6" class="mx-auto">
                <v-text-field
                    solo
                    v-model="oldPassword"
                    label="请输入旧密码"
                    prepend-inner-icon="mdi-key-chain-variant"
                    :append-icon="showOldPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showOldPassword ? 'text' : 'password'"
                    @click:append="showOldPassword = !showOldPassword"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row class="ma-0">
            <v-col cols="12" sm="8" md="6" class="mx-auto">
                <v-text-field
                    solo
                    v-model="newPassword"
                    label="请输入新密码"
                    hint="密码长度8-32位，且必须包含大小写字母/数字/符号任意三者组合，如：Ab123567、Aa@1234+"
                    counter
                    prepend-inner-icon="mdi-key-chain-variant"
                    :append-icon="showNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showNewPassword ? 'text' : 'password'"
                    @click:append="showNewPassword = !showNewPassword"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row class="ma-0">
            <v-col cols="12" sm="8" md="6" class="mx-auto">
                <v-text-field
                    solo
                    v-model="newPasswordConfirm"
                    label="请再次输入新密码"
                    prepend-inner-icon="mdi-key-chain-variant"
                    :append-icon="showNewPasswordConfirm ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showNewPasswordConfirm ? 'text' : 'password'"
                    @click:append="showNewPasswordConfirm = !showNewPasswordConfirm"
                ></v-text-field>
            </v-col>
        </v-row>
        <v-row class="ma-0">
            <v-col cols="12" sm="8" md="6" class="mx-auto">
                <v-btn color="#feb31a" dark class="ml-auto" @click="updatePassword" :loading="isUpdatingPassword">  
                    修改密码
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import {updateProfile} from '~/api/user';
import {mapGetters} from 'vuex'

export default {
    computed:{
        ...mapGetters({
            user: 'auth/user'
        }),
    },

    data: ()=> ({
        showOldPassword: false,
        showNewPassword: false,
        showNewPasswordConfirm: false,
        isUpdatingPassword: false,
        oldPassword: '',
        newPassword: '',
        newPasswordConfirm: '',
    }),

    created(){
        // this.$snackbar.showMessage({content: "asdf", color: "success"})
    },

    methods:{
        async updatePassword(){
            if(this.oldPassword.trim() == '' || this.newPassword.trim() == '' || this.newPasswordConfirm.trim() == ''){
                return this.$snackbar.showMessage({content: "输入字段为空", color: "error"})
            }
            if(this.newPassword !== this.newPasswordConfirm){
                return this.$snackbar.showMessage({content: "它与新密码和确认密码不匹配", color: "error"})
            }
            if(this.oldPassword == this.newPassword){
                return this.$snackbar.showMessage({content: "旧密码和新密码相同", color: "error"})
            }
            //check if contain number
            if(/\d/.test(this.newPassword) == false){
                return this.$snackbar.showMessage({content: "密码应包含数字", color: "error"})
            }
            //check if contain uppercase
            if(/[A-Z]/.test(this.newPassword) == false){
                return this.$snackbar.showMessage({content: "密码应至少包含1个大写字母", color: "error"})
            }
            //check if contain special character
            // if(/[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(this.newPassword) == false){
            //     return this.error('密码应包含至少1个特殊字符')
            // }
            //check if length is more than 8
            if(this.newPassword.length < 8 || this.newPassword.length > 32){
                return this.$snackbar.showMessage({content: "密码长度8-32位", color: "error"})
            }

            this.isUpdatingPassword = true;
            let payload = {
                oldPassword: this.oldPassword ,
                newPassword: this.newPassword,
                userId: this.user.id
            }
            await updateProfile(payload)
            .then((res) => {
                console.log("res", res)
                if(res.data.msg == 1){
                    this.$snackbar.showMessage({content: "操作成功", color: "success"})
                    this.oldPassword = '';
                    this.newPassword = '';
                    this.newPasswordConfirm = '';
                }
                else if(res.data.msg == 0){
                    this.$snackbar.showMessage({content: "旧密码和新密码不匹配", color: "error"})
                }
            }).catch((err) => {
                
            });
            this.isUpdatingPassword = false;
        },
    }
}
</script>

<style>

</style>