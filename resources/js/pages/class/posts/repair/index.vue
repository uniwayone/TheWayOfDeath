<template>
  <v-container class="pa-0" v-if="$isMobile()">
    <v-container class="pa-0 h-100 bg-white mb-16 pb-3 pt-12">
      <v-row class="ma-0 bg-white justify-center position-sticky-top-0">
        <v-icon
          @click="$router.go(-1)"
          size="35"
          class="position-absolute put-align-center"
          style="left: 0px; top:50%"
        >
          mdi-chevron-left
        </v-icon>
        <p class="mb-0 font-size-0-95 font-weight-bold pa-3">维修工单</p>
        <v-btn
          @click="submit"
          :loading="isSubmit"
          text
          color="#7879ff"
          class="position-absolute put-align-center"
          style="right: 0px; top:50%"
        >
          {{ lang.submit }}
        </v-btn>
      </v-row>
      <div class="cus-divider-light-gray-height"></div>
      <v-row class="ma-0 hover-cursor-point">
        <v-col cols="12" class="d-flex align-center">
          <v-text-field
            color="#7879ff"
            v-model="repairData.userName"
            label="姓名"
            hide-details
            readonly
            class="mt-0 pt-0"
          ></v-text-field>
        </v-col>
        <v-col cols="12" class="d-flex align-center">
          <v-text-field
            color="#7879ff"
            v-model="repairData.viewListName"
            label="发布位置"
            hide-details
            class="mt-0 pt-0"
          ></v-text-field>
        </v-col>
        <v-col cols="12" class="d-flex align-center">
          <v-select
            label="维修物品"
            :items="itemList"
            color="#7879ff"
            v-model="repairData.repairType"
            hide-details
            class="mt-0 pt-0"
          ></v-select>
        </v-col>
        <v-col cols="12" class="d-flex align-center">
          <v-text-field
            color="#7879ff"
            v-model="repairData.deadline"
            value="shiftData.prevName"
            label="发布时间"
            hide-details
            readonly
            class="mt-0 pt-0"
          ></v-text-field>
        </v-col>
      </v-row>
      <QuestionItem
        Label="请输入维修物品的详细信息，例如：桌子、椅子等"
        ref="child"
        @contentData="loadContentData"
      ></QuestionItem>
    </v-container>
  </v-container>
  <v-container class="pa-0" v-else>
    <div v-if="isPosting == true">
      <v-container class="px-10 z-index-2 banner-custom">
        <v-row>
          <v-col cols="6" md="4" class="d-flex align-center position-relative">
            <a @click="$router.go(-1)">
              <v-icon size="70" class="left-24p">
                mdi-chevron-left
              </v-icon>
            </a>
          </v-col>
          <v-col
            cols="6"
            md="4"
            class="d-flex align-center justify-start justify-md-center"
          >
            <h2>维修工单</h2>
          </v-col>
          <v-col cols="12" md="4" class="d-flex align-center justify-end">
            <v-btn text color="#999999" @click="tempList">
              可用模板 {{ templateCnt }}， 草稿 {{ draftCnt }}
            </v-btn>

            <v-btn
              tile
              dark
              color="#F19861"
              :loading="isDraft"
              @click="saveDraft"
            >
              {{ lang.saveDraft }}
            </v-btn>
            <v-btn
              dark
              tile
              color="#7879ff"
              @click="submit"
              :loading="isSubmit"
              class="mx-2"
            >
              提交
            </v-btn>
          </v-col>
        </v-row>
      </v-container>
      <v-container>
        <v-row class="ma-0 py-3 px-5 hover-cursor-point">
          <v-col cols="12" class="d-flex justify-space-between align-center">
            <p class="mb-0">姓名</p>
            <p class="mb-0">{{ repairData.userName }}</p>
          </v-col>
        </v-row>
        <!-- <v-row class="ma-0 py-3 px-5 hover-cursor-point">
                    <v-col cols="12" class="d-flex justify-space-between align-center">
                        <p class="mb-0">发布位置</p>
                        <p class="mb-0">{{repairData.viewList}}</p>
                    </v-col>
                </v-row> -->
        <v-row class="ma-0 py-3 px-5 hover-cursor-point">
          <v-col cols="12" class="d-flex justify-space-between align-center">
            <v-row>
              <v-col cols="6">
                <p class="mb-0">发布位置</p>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  solo
                  v-model="repairData.viewListName"
                  label="发布位置"
                  hide-details
                ></v-text-field>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
        <v-row class="ma-0 py-3 px-5 hover-cursor-point">
          <v-col cols="12" class="d-flex justify-space-between align-center">
            <v-row>
              <v-col cols="6">
                <p class="mb-0">维修物品</p>
              </v-col>
              <v-col cols="6">
                <v-select
                  :items="itemList"
                  solo
                  v-model="repairData.repairType"
                ></v-select>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
        <v-row class="ma-0 py-3 px-5 hover-cursor-point">
          <v-col cols="12" class="d-flex justify-space-between align-center">
            <v-row>
              <v-col cols="6">
                <p class="mb-0">发布时间</p>
              </v-col>
              <v-text-field
                solo
                v-model="repairData.deadline"
                value="shiftData.prevName"
                readonly
                dense
                hide-details
              ></v-text-field>
            </v-row>
          </v-col>
        </v-row>
        <v-row class="ma-0 py-3 px-5 hover-cursor-point">
          <QuestionItem
            Label="请输入维修物品的详细信息，例如：桌子、椅子等"
            ref="child"
            @contentData="loadContentData"
            :item="repairData.content[0]"
          ></QuestionItem>
        </v-row>
      </v-container>
    </div>
    <div v-else>
      <router-view></router-view>
    </div>
  </v-container>
</template>

<script>
import lang from "~/helper/lang.json";
import QuestionItem from "~/components/questionItem";
import { mapGetters } from "vuex";
import { createRepairData, getTemplateCnt, createTemplate } from "~/api/repair";
import quickMenu from "~/components/quickMenu";
export default {
  components: {
    QuestionItem,
    quickMenu
  },
  data: () => ({
    repairData: {
      userName: "",
      viewListName: "",
      repairType: "",
      content: [
        {
          text: "",
          imgUrl: [],
          otherUrl: [],
          videoUrl: []
        }
      ],
      deadline: "",
      schoolId: "",
      lessonId: null
    },
    itemList: ["设备维修", "保洁服务", "桶装水配送", "其他"],
    isSubmit: false,
    isDraft: false,
    lang,
    viewList: null,
    baseUrl: window.Laravel.base_url,
    isPosting: false,
    templateCnt: 0,
    draftCnt: 0
  }),
  computed: {
    currentPath() {
      return this.$route;
    },
    ...mapGetters({
      user: "auth/user"
    })
  },
  watch: {
    currentPath: {
      handler(val) {
        if (val.name == "posts.Crepair") {
          this.isPosting = true;
        }
        if (val.query.tempData) {
          console.log(JSON.parse(val.query.tempData));
          this.repairData.content = JSON.parse(val.query.tempData);
        }
      },
      deeper: true
    }
  },
  created() {
    if (this.currentPath.name == "posts.Crepair") {
      this.isPosting = true;
    }
    this.repairData.deadline = this.TimeView(Date.now());
    this.repairData.userName = this.user.name;
    this.repairData.schoolId = this.currentPath.params.schoolId;
    this.repairData.lessonId = this.currentPath.params.lessonId;
    getTemplateCnt({
      schoolId: this.currentPath.params.schoolId,
      lessonId: this.currentPath.params.lessonId
    })
      .then(res => {
        this.templateCnt = res.data.templateCnt;
        this.draftCnt = res.data.draftCnt;
      })
      .catch(err => {
        console.log(err.response);
      });
  },
  methods: {
    loadContentData(data) {
      if (data.text === "") {
        this.requiredText = true;
        this.repairData.content = [];
        return;
      }
      this.repairData.content.push(data);
    },
    async submit() {
      this.$refs.child.emitData();
      if (this.repairData.content.length == 0) {
        return this.$snackbar.showMessage({
          content: "主题字段为空。",
          color: "error"
        });
      }
      if (this.repairData.viewListName.trim() == "") {
        return this.$snackbar.showMessage({
          content: "主题字段为空。",
          color: "error"
        });
      }
      if (this.repairData.repairType == "") {
        return this.$snackbar.showMessage({
          content: "主题字段为空。",
          color: "error"
        });
      }
      // console.log(this.viewList)
      // return
      // this.repairData.viewListId = this.viewList.lessonId
      // this.repairData.viewListName = this.viewList.lessonName
      console.log(this.repairData);
      this.isSubmit = true;
      await createRepairData(this.repairData)
        .then(res => {
          console.log(res.data);
          this.isSubmit = false;
          if (this.$isMobile()) {
            this.$router.push({ name: "home" });
          } else {
            this.$router.push({ name: "classSpace.news" });
          }
        })
        .catch(err => {
          this.isSubmit = false;
          console.log(err.response);
        });
    },
    async saveDraft() {
      this.$refs.child.emitData();
      let draftData = {};
      draftData.tempType = 2;
      draftData.content = [];
      draftData.schoolId = this.currentPath.params.schoolId;
      if (this.currentPath.params.lessonId) {
        draftData.lessonId = this.currentPath.params.lessonId;
      }
      let currentTime = this.TimeView(new Date());
      draftData.title = "title-" + currentTime;
      draftData.description = "description-" + currentTime;
      console.log(draftData);
      if (this.repairData.content.length == 0) {
        return this.$snackbar.showMessage({
          content: this.lang.requireName,
          color: "error"
        });
      }
      draftData.content = this.repairData.content;
      this.isDraft = true;
      await createTemplate(draftData)
        .then(res => {
          console.log(res.data);
          this.isDraft = false;
          this.draftCnt++;
        })
        .catch(err => {
          console.log(err.response);
          this.isDraft = false;
        });
    },
    tempList() {
      this.isPosting = false;
      this.$router.push({ name: "Crepair.templateList" });
    },
    selectedLesson(val) {
      //console.log(val)
    },

    something() {}
  }
};
</script>

<style></style>
