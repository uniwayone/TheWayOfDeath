<template>
    <div class="">
        <div v-if="isGettingContactList" class="justify-center align-center d-flex pt-3 m-0" >
            <v-progress-circular
            indeterminate
            color="#7879ff"
            ></v-progress-circular>
        </div>
        <!-- <div v-else-if="isNoContactList" class="pa-3 text-center ">
            请添加新朋友
        </div> -->
        <v-list v-else three-line class="py-0 mb-16">
            <v-list-item-group
                color="#7879ff"
            >
                <template>
                    <v-list v-for="(group, i) in chatGroupList" :key="'A'+ i" class="py-0 position-relative">
                        <v-list-item @click="updatechatIn(group, i)">
                            <v-avatar size="50" color="#7879ff" class="mr-3" tile>
                                <span dark class="white--text headline"> {{group.room_id.roomName[0]}}</span>
                            </v-avatar>

                            <v-list-item-content>
                                <v-list-item-title>{{group.room_id.roomName}}</v-list-item-title>
                                <v-list-item-subtitle>asdf</v-list-item-subtitle>
                            </v-list-item-content>
                            <div v-if="group.new_msg_count !== 0" class="mr-8">
                                <v-badge
                                    color="red"
                                    :content="group.new_msg_count"
                                    >
                                </v-badge>
                            </div>
                        </v-list-item>
                        <!-- <v-menu rounded offset-y bottom left min-width="200" origin="top right" transition="scale-transition">
                            <template v-slot:activator="{ attrs, on }">
                                <v-icon class="position-absolute" v-bind="attrs" v-on="on" color="indigo" style="top: 10px; right: 10px;">
                                    mdi-dots-horizontal-circle-outline
                                </v-icon>
                            </template>

                            <v-list>
                                <v-list-item @click="leaveGroup(group)"><v-list-item-icon> <v-icon>mdi-arrow-right-bold-box-outline</v-icon> </v-list-item-icon> <v-list-item-title > 离开团体</v-list-item-title> </v-list-item>
                                <v-list-item v-if="group.room_id.userId == currentUser.id" @click="removeGroup(group)"><v-list-item-icon> <v-icon>mdi-trash-can-outline</v-icon> </v-list-item-icon>  <v-list-item-title > 删除群组</v-list-item-title> </v-list-item>
                            </v-list>
                        </v-menu> -->
                        
                        <v-divider inset ></v-divider>
                    </v-list>
                    <v-list v-if="bot !== null" class="py-0 position-relative">
                        <v-list-item 
                            @click="updatechatwith(bot)" 
                        >   
                            <v-avatar  class="mr-3" size="50" color="#7879ff">
                                <v-img :src="`${baseUrl}${bot.user.avatar}`" :alt="bot.user.name[0]" class="chat-user-avatar"></v-img>
                            </v-avatar>
                            <v-list-item-content>
                                <v-list-item-title class="d-flex justify-space-between">
                                    <p class="mb-0"> {{bot.user.name}} </p>
                                    <p class="mb-0 font-size-0-8" v-if="bot.last_time !== null"> 
                                        <timeago :datetime="convertTime(bot.last_time)" :auto-update="60"></timeago>
                                        <!-- <timeago :datetime="convertTime(user.last_time)" locale="zhCN" :auto-update="60"></timeago>  -->
                                    </p>
                                    <p class="mb-0" v-else></p>
                                </v-list-item-title>
                                <v-list-item-subtitle class="d-flex justify-space-between line-height-1-7">
                                    <p class="mb-0" v-if="bot.last_message !== null&& bot.last_message == 'sammie-image'" ><span class="d-flex align-center"><v-icon left>mdi-file-image-outline</v-icon>图片</span></p>
                                    <p class="mb-0" v-else-if="bot.last_message !== null&& bot.last_message == 'sammie-video'"><span class="d-flex align-center"><v-icon left>mdi-file-video-outline</v-icon>视频</span></p>
                                    <p class="mb-0" v-else-if="bot.last_message !== null&& bot.last_message == 'sammie-file'"><span  class="d-flex align-center"><v-icon left>mdi-file-upload-outline</v-icon>文档</span></p>   
                                    <p class="mb-0 w-50 over-flow-text" v-else>{{bot.last_message}}</p>
                                    <div v-if="bot.new_msg_count !== 0" class="mr-8">
                                        <v-badge
                                            color="red"
                                            :content="bot.new_msg_count"
                                            >
                                        </v-badge>
                                    </div>
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider inset></v-divider>
                    </v-list>
                    <v-list v-for="(user, index) in filteredContacts" :key="index" class="py-0 position-relative">
                        <v-list-item 
                            @click="updatechatwith(user)" 
                            v-long-press="1000"
                            @long-press-start="onLongPressStart(index)"
                            @touchstart="startTouchContact"
                            @touchend="stopTouchContact"
                            :ref="`contact-user-${index}`"
                            :id="`contact-user-${index}`"
                        >
                            <v-badge bordered bottom v-if="checkOnline(user.user.id)"
                                color="light-green accent-3" dot
                                offset-x="10"
                                offset-y="10" class="mr-3"
                            >
                                <v-avatar size="50" color="#7879ff">
                                    <v-img v-if="user.user.avatar !== '/'" :src="`${baseUrl}${user.user.avatar}`" :alt="user.user.name[0]" class="chat-user-avatar"></v-img>
                                    <span dark v-else class="white--text headline"> {{user.user.name[0]}}</span>
                                </v-avatar>
                            </v-badge>
                            <v-badge bordered bottom v-else
                                color="blue-grey lighten-2" dot
                                offset-x="10"
                                offset-y="10" class="mr-3"
                            >
                                <v-avatar size="50" color="#7879ff">
                                    <v-img v-if="user.user.avatar !== '/'" :src="`${baseUrl}${user.user.avatar}`" :alt="user.user.name[0]" class="chat-user-avatar"></v-img>
                                    <span dark v-else class="white--text headline"> {{user.user.name[0]}}</span>
                                </v-avatar>
                            </v-badge>
                            
                            <v-list-item-content>
                                <v-list-item-title class="d-flex justify-space-between">
                                    <p class="mb-0"> {{user.user.name}} </p>
                                    <p class="mb-0 font-size-0-8" v-if="user.last_time !== null"> 
                                        <timeago :datetime="convertTime(user.last_time)" :auto-update="60"></timeago>
                                        <!-- <timeago :datetime="convertTime(user.last_time)" locale="zhCN" :auto-update="60"></timeago>  -->
                                    </p>
                                    <p class="mb-0" v-else></p>
                                </v-list-item-title>
                                <v-list-item-subtitle class="d-flex justify-space-between line-height-1-7">
                                    <p class="mb-0" v-if="user.last_message !== null&& user.last_message == 'sammie-image'" ><span class="d-flex align-center"><v-icon left>mdi-file-image-outline</v-icon>图片</span></p>
                                    <p class="mb-0" v-else-if="user.last_message !== null&& user.last_message == 'sammie-video'"><span class="d-flex align-center"><v-icon left>mdi-file-video-outline</v-icon>视频</span></p>
                                    <p class="mb-0" v-else-if="user.last_message !== null&& user.last_message == 'sammie-file'"><span  class="d-flex align-center"><v-icon left>mdi-file-upload-outline</v-icon>文档</span></p>   
                                    <p class="mb-0 w-50 over-flow-text" v-else>{{user.last_message}}</p>
                                    <!-- <div v-if="user.new_msg_count !== 0" class="mr-8">
                                        <v-badge
                                            color="red"
                                            :content="user.new_msg_count"
                                            >
                                        </v-badge>
                                    </div> -->
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                        <v-divider inset v-if="index < filteredContacts.length - 1" ></v-divider>
                    </v-list>
                    <div class="v-overlay__scrim-cus" id="v-overlay__scrim-cus">
                        <div class="mo-chat-contact-cus-menu" id="mo-chat-contact-cus-menu" v-ripple @click="removeContact"  v-click-outside="closeMoChatCusMenuOverlay">
                            <p class="mb-0 pa-2 px-5 font-color-gray">删除</p> 
                        </div>
                    </div>
                </template>
            </v-list-item-group>
        </v-list>
    </div>
</template>

<script>
import lang from '~/helper/lang.json'
import { mapGetters } from 'vuex';
import pinyin from 'js-pinyin'
import { getUserList, getContactList, addUserToContact, postNewMsgCount, postNewGroup, removeContactUser, leaveGroup, removeGroup } from '~/api/chat'
import LoginWithGithubVue from '../../../components/LoginWithGithub.vue';
export default {
    props:{
        chatto: {
            type: Number,
            required: false,
        },
        chatin: {
            type: Number,
            required: false,
        },
    },

    data: ()=> ({
        lang,
        model: null,
        value: 0,
        addDialog: false,
        baseUrl: window.Laravel.base_url,
        isGettingContactList: false,
        isNoContactList: false,
        postFailed : false,
        postSuccess : false,
        isAdding: false,
        willAddToContactUser : {
            contactId:null,
        },
        errorMessage: '',
        successMessage: '',
        searchContact:'',
        users:[],
        contactList:[],
        chatGroupList:[],
        addContactUserList:[],
        totalNewMessageCount:0,
        activeUserList: [],

        newGroup: [],
        isCreatingNewGroup: false,
        groupNameDialog: false,
        groupName: '',

        touchDuration : 1000,
        timer: null,
        globalTouchEvent: null,
        moChatCusMenuOverlay: false,
        isOpenedMenu: false,
        removeIndex: null,
    }),

    async created(){
        this.$timeago.locale = 'zhCN'
        this.listen();
        if(this.usersStore !== null){
            this.users = this.usersStore;
        }
        else{
            this.isGettingContactList = true;
            await getUserList()
            .then((res) => {
                this.users = res.data;
                this.users = this.users.users.filter((user) => user.id !== this.currentUser.id);
                this.users = this.users.filter((user) => user.avatar !== '/asset/img/bot/bot1.png');
                this.users.map( user => {
                    user['isSelected'] = false;
                })
                this.addressedUsers = this.pySort(this.users, 'name')
                this.$store.dispatch('chat/storeUsers',this.users)
                this.$store.dispatch('chat/storeAddressedUsers',this.addressedUsers)
            }).catch((err) => {
                console.log(err);
            });
            this.isGettingContactList = false;
        }
        if(this.contactListStore !== null){
            this.contactList = this.contactListStore;
            this.chatGroupList = this.chatGroupListStore;
            this.totalNewMessageCount = this.totalNewMessageCountStore;
        }
        else{
            this.isGettingContactList = true;
            await getContactList()
            .then((res) => {
                this.chatGroupList = res.data.chatGroups;
                this.contactList = res.data.contactUsers;

                this.contactList.map(contact => {
                    if(contact.user.avatar == '/asset/img/bot/bot1.png'){
                        this.$store.dispatch('chat/storeBot',contact)
                    }
                })
                this.contactList = this.contactList.filter((user) => user.user.avatar !== '/asset/img/bot/bot1.png');

                this.$store.dispatch('chat/storeContactList',this.contactList)
                this.$store.dispatch('chat/storeGroupList',this.chatGroupList)
                if(this.chatGroupList.length == 0 && this.contactList.length == 0){
                    this.isNoContactList = true;
                }
                for(let i = 0; i < this.contactList.length ; i++){
                    this.totalNewMessageCount = this.totalNewMessageCount + this.contactList[i].new_msg_count;
                }
                for(let i = 0; i < this.chatGroupList.length ; i++){
                    this.totalNewMessageCount = this.totalNewMessageCount + this.chatGroupList[i].new_msg_count;
                }
                this.$store.dispatch('chat/storeTotalNewMsgCount',this.totalNewMessageCount)
                // this.$emit("updatechatwith", this.contactList[0]);
            }).catch((err) => {
                console.log(err);
            });
            this.isGettingContactList = false;
        }
        // this.model = this.chatGroupList.length;
    },

    mounted(){
        Echo.join('chats')
            .here(user=>{
                this.activeUserList = user;
            })
            .joining(user=>{
                this.activeUserList.push(user);
            })
            .leaving(user=>{
                this.activeUserList = this.activeUserList.filter(u => u.id != user.id);
            })
    },

    computed:{
        ...mapGetters({
            currentUser: 'auth/user',
            usersStore: 'chat/usersStore',
            contactListStore: 'chat/contactListStore',
            chatGroupListStore: 'chat/chatGroupListStore',
            totalNewMessageCountStore: 'chat/totalNewMessageCountStore',
            bot: 'chat/bot'
        }),

        filteredContacts(){
            if(this.searchContact){
                return this.contactList.filter((item) => {
                    return (item.user.name.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.subject.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.description.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.created_at.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        
                });
            } else{
                return this.contactList;
            }
        },
    },
    methods:{
        listen(){
            Echo.private('group')
                .listen('NewGroup', (e) => {
                    if(e.group.room_id.invited !== null){
                        let invitedArr = e.group.room_id.invited;
                        if(invitedArr.includes(this.currentUser.id)){
                            this.isNoContactList = false;
                            this.chatGroupList.unshift(e.group);
                        }
                    }
                    else if(e.group.room_id.invited == null){
                        let removedGroupId = e.group.roomId;
                        for (let i = 0; i < this.chatGroupList.length ; i++){
                            if( this.chatGroupList[i].roomId == removedGroupId){
                                this.chatGroupList.splice(i, 1);
                            }
                        }
                        if(this.chatGroupList.length == 0 && this.contactList.length == 0){
                            this.isNoContactList = true;
                        }
                    }
                });
            Echo.join('chats')
                .here(user=>{
                    this.activeUserList = user;
                })
                .joining(user=>{
                    this.activeUserList.push(user);
                })
                .leaving(user=>{
                    this.activeUserList = this.activeUserList.filter(u => u.id != user.id);
                })
            Echo.private('newMessage.'+ this.currentUser.id)
                .listen('NewMessage', (message) => {
                    console.log(message)
                    if ( message.message.to == this.currentUser.id) {
                        for(let i = 0; i < this.contactList.length; i++){
                            if( message.message.from.id == this.contactList[i].contactUserId ){
                                this.totalNewMessageCount = this.totalNewMessageCount + 1;
                                // this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
                                this.$store.dispatch('chat/storeTotalNewMsgCount',this.totalNewMessageCount)
                                this.contactList[i].new_msg_count = this.contactList[i].new_msg_count + 1;
                                console.log(this.contactList[i])
                                postNewMsgCount({new_msg_count:this.contactList[i]});

                                if(message.message.text !== null){
                                    this.contactList[i].last_messsage = message.message.text;
                                    this.$store.dispatch('chat/storeContactList',this.contactList)
                                }
                            }
                        }
                    }
                    else if(message.message.roomId !== null){
                        if ( (((message.message.roomId.invited)).includes(this.currentUser.id) || message.message.roomId.userId == this.currentUser.id ) && message.message.from.id !== this.currentUser.id  ) {
                            for(let i = 0; i < this.chatGroupList.length; i++){
                                if( message.message.roomId == this.chatGroupList[i].roomId ){
                                    this.totalNewMessageCount = this.totalNewMessageCount + 1;
                                    // this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
                                    this.$store.dispatch('chat/storeTotalNewMsgCount',this.totalNewMessageCount)
                                    this.chatGroupList[i].new_msg_count = this.chatGroupList[i].new_msg_count + 1;
                                    postNewMsgCount({new_msg_count:this.chatGroupList[i]})
                                }
                            }
                        }
                    }
                })
                
        },

        checkOnline(userId){
            for(let i = 0; i < this.activeUserList.length; i++){
                if(this.activeUserList[i].id == userId){
                    return true;
                }
            }
            return false;
        },

        updatechatwith(userInfo) {
            for(let i = 0; i < this.contactList.length; i++){
                if( userInfo.user.id == this.contactList[i].contactUserId ){
                    this.totalNewMessageCount = this.totalNewMessageCount - this.contactList[i].new_msg_count;
                    this.$store.dispatch('chat/storeTotalNewMsgCount',this.totalNewMessageCount)
                    this.contactList[i].new_msg_count = 0;
                    postNewMsgCount({new_msg_count:this.contactList[i]})
                }
            }
            this.$emit("updatechatwith", userInfo);
        },

        updatechatIn(group, index) {
            this.$emit("updatechatIn", group);
            for(let i = 0; i < this.chatGroupList.length; i++){
                if( group.roomId == this.chatGroupList[i].roomId ){
                    this.totalNewMessageCount = this.totalNewMessageCount - this.chatGroupList[i].new_msg_count;
                    this.$store.dispatch('chat/storeTotalNewMsgCount',this.totalNewMessageCount)
                    this.chatGroupList[i].new_msg_count = 0;
                    postNewMsgCount({new_msg_count:this.chatGroupList[i]})
                }
            }
        },

        removeUserFromContactList(user){
            let payload = {
                userId : user.id
            }
            removeContactUser(payload)
            .then(res=>{
                if(res.data.msg == 1){
                    let removedUserId = user.id;
                    for (let i = 0; i < this.contactList.length ; i++){
                        if( this.contactList[i].user.id == removedUserId){
                            this.contactList.splice(i, 1);
                        }
                    }
                }
                if(this.chatGroupList.length == 0 && this.contactList.length == 0){
                    this.isNoContactList = true;
                }
            })
            .catch(err=>{
                console.log(err.response);
            })
        },

        leaveGroup(group){
            let payload = {
                roomId : group.roomId
            }
            leaveGroup(payload)
            .then(res=>{
                if(res.data.msg == 1){
                    let removedGroupId = res.data.roomId;
                    for (let i = 0; i < this.chatGroupList.length ; i++){
                        if( this.chatGroupList[i].roomId == removedGroupId){
                            this.chatGroupList.splice(i, 1);
                        }
                    }
                }
                if(this.chatGroupList.length == 0 && this.contactList.length == 0){
                    this.isNoContactList = true;
                }
            })
            .catch(err=>{
                console.log(err.response);
            })
        },

        removeGroup(group){
            let payload = {
                roomId : group.roomId
            }
            removeGroup(payload)
            .then(res=>{
                if(res.data.msg == 1){
                    let removedGroupId = group.roomId;
                    for (let i = 0; i < this.chatGroupList.length ; i++){
                        if( this.chatGroupList[i].roomId == removedGroupId){
                            this.chatGroupList.splice(i, 1);
                        }
                    }
                }
                if(this.chatGroupList.length == 0 && this.contactList.length == 0){
                    this.isNoContactList = true;
                }
            })
            .catch(err=>{
                console.log(err.response);
            })
        },
        isChinese (temp) {
            let re = /[^\u4E00-\u9FA5$]/
            if (re.test(temp)) { return false }
            return true
        },
        isChar (char) {
            let reg = /[A-Za-z]/
            if (!reg.test(char)) { return false }
            return true
        },
        pySort (arr, attrName, empty) {
            if (!attrName) {
                    console.error ( 'pass in the sorting property')
                return null
            }
            if (!String.prototype.localeCompare) { return null }
            let letters = 'ABCDEFGHJKLMNOPQRSTWXYZ#'.split('')
            let zh = '  It '.split('')
            let arrList = []
            for (let m = 0; m < arr.length; m++) {
                arrList.push(arr[m])
            }
            let result = []
            let curr = {}
            for (let i = 0; i < letters.length; i++) {
                curr = {letter: letters[i], data: []}
                if (i !== 23) {
                    for (let j = 0; j < arrList.length; j++) {
                                let initial = arrList[j][attrName].charAt(0)// intercept the first character
                                if (arrList[j][attrName].charAt(0) === letters[i] || arrList[j][attrName].charAt(0) === letters[i].toLowerCase()) { // the first character is in English
                                    curr.data.push(arrList[j]) // English alphabet
                                } else if (zh[i] !== '*' && this.isChinese(initial)) { // Determine if there is no Chinese character, is it Chinese?
                            if (pinyin.getCamelChars(initial).charAt(0) === letters[i]) {
                                curr.data.push(arrList[j])
                            }
                        }
                    }
                } else {
                    for (let k = 0; k < arrList.length; k++) {
                                let ini = arrList[k][attrName].charAt(0) // intercept the first character
                                if (!this.isChar(ini) && !this.isChinese(ini)) { // The first character is not a Chinese character (such as numbers, special characters, etc.)
                        curr.data.push(arrList[k])
                        }
                    }
                }
                if (empty || curr.data.length) {
                    result.push(curr)
                }
            }
            return result
        },
        convertTime(time){
            return new Date(time);
        },

        onLongPressStart(index){
            let el = document.getElementById(`contact-user-${index}`);
            
            this.removeIndex = index;

        },

        startTouchContact(e){
            this.globalTouchEvent = e;
            this.timer = setTimeout(this.onLongTouch, this.touchDuration); 
        },

        stopTouchContact(e){        
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }    
        },

        onLongTouch(){
            
            let moChatCusMenu = document.getElementById('mo-chat-contact-cus-menu');
            let moChatCusMenuOverlay = document.getElementById('v-overlay__scrim-cus');
            $(moChatCusMenuOverlay).css({"visibility": "visible"});
            
            $(moChatCusMenu).css({"top": this.globalTouchEvent.touches[0].pageY - 48});
            if(this.globalTouchEvent.touches[0].pageX - 72 < 0){
                $(moChatCusMenu).css({"left": "20px"});
            }
            else{
                $(moChatCusMenu).css({"left": this.globalTouchEvent.touches[0].pageX - 72});
            }
            $(moChatCusMenu).css({"visibility": "visible"});
            this.isOpenedMenu = true;
        },

        closeMoChatCusMenuOverlay(){
            if(this.isOpenedMenu = false){
                return;
            }
            else{
                let moChatCusMenu = document.getElementById('mo-chat-contact-cus-menu');
                let moChatCusMenuOverlay = document.getElementById('v-overlay__scrim-cus');
                $(moChatCusMenu).css({"visibility": "hidden"});
                $(moChatCusMenuOverlay).css({"visibility": "hidden"});

                this.isOpenedMenu = false
            }
        },

        removeContact(){
            let payload = {
                userId : this.contactList[this.removeIndex].contactUserId
            }
            removeContactUser(payload)
            .then(res=>{
                if(res.data.msg == 1){
                    let removedUserId = payload.userId;
                    for (let i = 0; i < this.contactList.length ; i++){
                        if( this.contactList[i].user.id == removedUserId){
                            this.contactList.splice(i, 1);
                        }
                    }
                    this.$store.dispatch('chat/storeContactList',this.contactList)
                    this.closeMoChatCusMenuOverlay();
                }
                if(this.chatGroupList.length == 0 && this.contactList.length == 0){
                    this.isNoContactList = true;
                }
            })
            .catch(err=>{
                console.log(err.response);
            })
            
        }
    }
}
</script>

<style>

</style>