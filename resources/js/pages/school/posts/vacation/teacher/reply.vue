<template>
    <v-container v-if="$isMobile()">
        <v-row class="ma-0">
            <v-col cols="12" class="mo-glow d-flex align-center">
                <v-avatar class="mo-glow-small-shadow" >
                    <v-img :src="`${baseUrl}/asset/img/icon/请假.png`" alt="postItem" width="48" height="48" ></v-img>
                </v-avatar>
                <h2 class="ml-3">请假单审批</h2>
            </v-col>
        </v-row>
        <v-row class="ma-0 mo-glow mt-5">
            <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                <span class="mr-14 pa-3 mo-glow-inverse">请假人</span>
                <v-text-field
                    class="mo-glow-v-text"
                    solo
                    v-model="newVacationData.studentName"
                    label="请假人"
                    hide-details
                    readonly
                ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                <span class="mr-14 pa-3 mo-glow-inverse">审批人</span>
                <v-text-field
                    class="mo-glow-v-text"
                    solo
                    v-model="newVacationData.teacherName"
                    label="审批人"
                    hide-details
                    readonly
                ></v-text-field>
            </v-col>
        
            <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                <span class="mr-10 pa-3 mo-glow-inverse">开始时间</span>
                <v-datetime-picker 
                    label="开始时间" 
                    v-model="newVacationData.startTime"
                    :okText='lang.ok'
                    :clearText='lang.cancel'
                    :disabled="true"
                > </v-datetime-picker>
            </v-col>
            <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                <span class="mr-10 pa-3 mo-glow-inverse">结束时间</span>
                <v-datetime-picker 
                    label="结束时间" 
                    v-model="newVacationData.endTime"
                    :okText='lang.ok'
                    :clearText='lang.cancel'
                    :disabled="true"
                > </v-datetime-picker>
            </v-col>
        
            <v-col cols="12" class="d-flex justify-space-between align-start">
                <span class="mr-10 pa-3 mo-glow-inverse ">请假原因</span>
                <v-textarea
                    class="mo-glow-v-text"
                    solo
                    v-model="newVacationData.reason"
                    label="请假原因"
                    hide-details
                    readonly
                ></v-textarea>
            </v-col>
        
            <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                <span class="pa-3 mo-glow">病假</span>
                <v-switch
                    v-model="newVacationData.reasonFlag"
                    color="error"
                    readonly
                    hide-details
                    class="pt-0 mt-0"
                ></v-switch>
                <span class="pa-3 mo-glow">事假</span>
            </v-col>
        
            <v-col cols="12" sm="6" class="d-flex justify-space-around">
                <span class="mr-10">假期类型</span>
                <span class="mr-auto " v-if="newVacationData.reasonFlag == false">病假</span>
                <span class="mr-auto " v-else >事假</span>
                <v-checkbox
                    v-model="newVacationData.isHeat"
                    label="是否发热"
                    color="error"
                    hide-details
                    readonly
                    class="mt-0 pt-0"
                ></v-checkbox>
            </v-col>
        
            <v-col v-if="newVacationData.reasonFlag == false" cols="12" class="d-flex justify-space-between align-start">
                <span class="mr-16 pa-3 mo-glow-inverse">症状</span>
                <v-textarea
                    solo
                    v-model="newVacationData.painDesc"
                    label="症状"
                    hide-details
                    readonly
                    class="mo-glow-v-text"
                ></v-textarea>
            </v-col>
            <v-col cols="12" class="d-flex align-center mb-10">
                <v-btn small fab class="mo-glow mr-auto" style="color:#eb6846" @click="$router.go(-1)"><v-icon>mdi-undo-variant</v-icon></v-btn>
                <v-btn
                    dark
                    color="#7879ff"
                    rounded
                    @click="allowVacation"
                    class="mr-3 ml-auto"
                >
                    <v-icon left>
                        mdi-check-decagram-outline 
                    </v-icon>
                    通过
                </v-btn>
                <v-btn
                    dark
                    color="error"
                    rounded
                    @click="denyVacation"
                    
                >
                    <v-icon left>
                        mdi-hand-left
                    </v-icon>
                    驳回
                </v-btn>
                
            </v-col>
        </v-row>
    </v-container>
    <v-container class="pa-0" v-else>
        <v-banner class=" mb-10 z-index-2" color="white" sticky elevation="20">
            <div class="d-flex align-center">
                <v-avatar
                    class="ma-3 ml-3"
                    size="50"
                    tile
                >
                    <v-img :src="`${baseUrl}/asset/img/icon/请假.png`" alt="postItem" ></v-img>
                </v-avatar>
                <h2>请假单审批</h2>
            </div>
            <template v-slot:actions>
            <v-btn
                dark
                color="#7879ff"
                class="mr-md-8"
                tile
                @click="allowVacation"
            >
                <v-icon left>
                    mdi-check-decagram-outline 
                </v-icon>
                通过
            </v-btn>
            <v-btn
                dark
                color="error"
                class="mr-8"
                tile
                @click="denyVacation"
            >
                <v-icon left>
                    mdi-hand-left
                </v-icon>
                驳回
            </v-btn>
            </template>
        </v-banner>
        <v-container class="pa-10">
            <v-row>
                <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                    <span class="mr-14">请假人</span>
                    <v-text-field
                        solo
                        v-model="newVacationData.studentName"
                        label="请假人"
                        hide-details
                        readonly
                    ></v-text-field>
                </v-col>
                <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                    <span class="mr-14">审批人</span>
                    <v-text-field
                        solo
                        v-model="newVacationData.teacherName"
                        label="审批人"
                        hide-details
                        readonly
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                    <span class="mr-10">开始时间</span>
                    <v-datetime-picker 
                        label="开始时间" 
                        v-model="newVacationData.startTime"
                        :okText='lang.ok'
                        :clearText='lang.cancel'
                        :disabled="true"
                    > </v-datetime-picker>
                </v-col>
                <v-col cols="12" sm="6" class="d-flex justify-space-between align-center">
                    <span class="mr-10">结束时间</span>
                    <v-datetime-picker 
                        label="结束时间" 
                        v-model="newVacationData.endTime"
                        :okText='lang.ok'
                        :clearText='lang.cancel'
                        :disabled="true"
                    > </v-datetime-picker>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" class="d-flex justify-space-between align-start">
                    <span class="mr-10 mt-3">请假原因</span>
                    <v-textarea
                        solo
                        v-model="newVacationData.reason"
                        label="请假原因"
                        hide-details
                        readonly
                    ></v-textarea>
                </v-col>
            </v-row>
            <v-row class="d-flex justify-center">
                <v-col cols="12" sm="6" class="d-flex justify-space-around">
                    <span class="hover-cursor-point">病假</span>
                    <v-switch
                        v-model="newVacationData.reasonFlag"
                        color="#7879ff"
                        readonly
                        hide-details
                        inset
                        class="pt-0 mt-0"
                    ></v-switch>
                    <span class="hover-cursor-point">事假</span>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="6" class="d-flex justify-space-around">
                    <span class="mr-10">假期类型</span>
                    <span class="mr-auto " v-if="newVacationData.reasonFlag == false">病假</span>
                    <span class="mr-auto " v-else >事假</span>
                    <v-checkbox
                        v-model="newVacationData.isHeat"
                        label="是否发热"
                        color="error"
                        hide-details
                        readonly
                        class="mt-0 pt-0"
                    ></v-checkbox>
                </v-col>
            </v-row>
            <v-row v-if="newVacationData.reasonFlag == false">
                <v-col cols="12" class="d-flex justify-space-between align-start">
                    <span class="mr-16 mt-3">症状</span>
                    <v-textarea
                        solo
                        v-model="newVacationData.painDesc"
                        label="症状"
                        hide-details
                        readonly
                        class="ml-2"
                    ></v-textarea>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
</template>

<script>

import { mapGetters } from 'vuex'
import {getCurrentData,updateVacationData} from '~/api/vacation'
import lang from '~/helper/lang.json'

export default {
    data: () => ({
        lang,
        baseUrl: window.Laravel.base_url,
        newVacationData : {
            // studentName:'something',
            // teacherName:'something',
            // reasonFlag: true,
            // startTime: '2021-03-08 09:25',
            // endTime: '2021-03-08 10:25',
            // reason: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            // painDesc: 'asdf',
            // isHeat: true
        },
    }),

    components: {
        
    },

    computed: {
        ...mapGetters({
            user : 'auth/user'
        }),
        currentPath(){
            return this.$route
        }
    },

    async created(){
        //you need to get teacher's name from server or vuex...
        // this.newVacationData.teacherName = this.....
        // this.isLoading = true
        await getCurrentData({vId:this.currentPath.params.vId}).then(res=>{
            this.newVacationData = res.data
            this.newVacationData.startTime = this.TimeView(this.newVacationData.startTime)
            this.newVacationData.endTime = this.TimeView(this.newVacationData.endTime)
            this.isLoading = false
        }).catch(err=>{
            console.log(err.response)
        })
        this.isLoading = false
        // this.newVacationData.studentName = this.user.name;
    },

    mounted(){
        
    },

    methods:{
        postVacationData(){
            //console.log(this.newVacationData);
        },

        allowVacation(){
            updateVacationData({status:'allow',vId:this.currentPath.params.vId}).then(res=>{
                this.$router.push({name:'schoolSpace.news'})
            }).catch(err=>{
                console.log(err.response)
            })
        },

        denyVacation(){
            updateVacationData({status:'deny',vId:this.currentPath.params.vId}).then(res=>{
                this.$router.push({name:'schoolSpace.news'})
            }).catch(err=>{
                console.log(err.response)
            })
        },
        
    }
}
</script>

<style>

</style>