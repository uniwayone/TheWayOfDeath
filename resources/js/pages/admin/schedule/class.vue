<template>
  <v-container class="pa-0" v-if="$isMobile()">
    <v-container class="pt-0 px-0 h-100 bg-white mb-16 pb-10-px pt-12">
      <v-row class="ma-0 bg-white justify-center position-sticky-top-0">
        <v-icon
          @click="$router.go(-1)"
          size="35"
          class="position-absolute put-align-center"
          style="left: 0px; top:50%"
        >
          mdi-chevron-left
        </v-icon>
        <p class="mb-0 font-size-0-95 font-weight-bold pa-3">课程表</p>
        <div
          class="d-flex align-center position-absolute put-align-center"
          style="right: 0px; top:50%"
        >
          <v-btn
            v-if="isEditable == false"
            @click="editable"
            small
            text
            color="#49d29e"
          >
            修改
          </v-btn>
          <v-btn
            v-if="isEditable == true"
            @click="onSubmit"
            :loading="isLoadingNewData"
            small
            text
            color="#7879ff"
          >
            保存
          </v-btn>
        </div>
      </v-row>
      <div class="cus-divider-light-gray-height"></div>
      <v-row class="ma-0">
        <v-col cols="12">
          <v-data-table
            :headers="headers"
            :items="scheduleData"
            :loading="isLoadingSchoolData"
            loading-text="正在加载..."
            sort-by="calories"
            class="elevation-1"
          >
            <template v-slot:body="{ items, headers }">
              <tbody>
                <tr v-for="(item, idx) in items" :key="idx">
                  <td v-for="(header, key) in headers" :key="key">
                    <div v-if="key == 0">
                      <!-- something -->
                      {{ item.ord }}
                    </div>
                    <div v-else-if="key == 8">
                      <v-icon
                        small
                        color="success"
                        class="mr-2"
                        :disabled="!isEditable"
                        @click="editItem(item)"
                      >
                        mdi-pencil
                      </v-icon>
                      <v-icon
                        small
                        color="error"
                        :disabled="!isEditable"
                        @click="deleteItem(item)"
                      >
                        mdi-delete
                      </v-icon>
                    </div>
                    <v-select
                      v-else
                      :items="subjectItem"
                      :menu-props="{ top: false, offsetY: true }"
                      item-text="subjectName"
                      item-value="subjectName"
                      v-model="item[header.value]"
                      hide-details
                      :disabled="!isEditable"
                    >
                    </v-select>
                  </td>
                </tr>
              </tbody>
            </template>
            <template v-slot:no-data>
              <p>暂无</p>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
  </v-container>
  <v-container class="pa-0" v-else>
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
          <h2>课程表</h2>
        </v-col>
        <v-col cols="12" md="4" class="d-flex align-center justify-end">
          <v-btn
            color="#f19861"
            dark
            class="mb-2 ml-2"
            tile
            v-if="isEditable == false"
            @click="editable"
          >
            <v-icon left>
              mdi-check
            </v-icon>
            修改
          </v-btn>
          <v-btn
            color="#7879ff"
            dark
            class="mb-2 ml-2"
            tile
            v-if="isEditable == true"
            :loading="isLoadingNewData"
            @click="onSubmit"
          >
            <v-icon left>
              mdi-alert-circle-outline
            </v-icon>
            <!-- 无法修改 -->
            <!-- {{lang.submit}} -->
            保存
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
    <v-row class="mt-5">
      <v-col cols="12" class="px-10">
        <v-data-table
          :headers="headers"
          :items="scheduleData"
          hide-default-footer
          :loading="isLoadingSchoolData"
          loading-text="正在加载..."
          sort-by="calories"
          class="elevation-1"
        >
          <!-- <template v-slot:top>
                    <v-toolbar
                        flat
                    >
                        <v-toolbar-title><strong>{{someData.grade.className}}2021学下学期课程表</strong></v-toolbar-title>
                        <v-toolbar-title><strong>{{sessionName}}</strong></v-toolbar-title>
                        <v-divider
                        class="mx-4"
                        inset
                        vertical
                        ></v-divider>
                        <v-spacer></v-spacer>

                        <v-btn
                            color="#f19861"
                            dark
                            class="mb-2 ml-2"
                            tile
                            v-if="isEditable == false"
                            @click="editable"
                            >
                            <v-icon left>
                                mdi-check
                            </v-icon>
                            修改
                        </v-btn>
                        <v-btn
                            color="#7879ff"
                            dark
                            class="mb-2 ml-2"
                            tile
                            v-if="isEditable == true"
                            :loading="isLoadingNewData"
                            @click="onSubmit"
                            >
                            <v-icon left>
                                mdi-alert-circle-outline
                            </v-icon>
                        
                            {{lang.submit}}
                            保存
                        </v-btn>
                        <v-btn
                            color="#7879ff"
                            dark
                            class="mb-2 ml-2"
                            tile
                            :loading="isLoadingNewData"
                            @click="onSubmit"
                            >
                            {{lang.submit}}
                        </v-btn>
                    </v-toolbar>
                </template> -->

          <template v-slot:body="{ items, headers }">
            <tbody>
              <tr v-for="(item, idx) in items" :key="idx">
                <td v-for="(header, key) in headers" :key="key">
                  <div v-if="key == 0">
                    <!-- something -->
                    {{ item.ord }}
                  </div>
                  <div v-else-if="key == 8">
                    <v-icon
                      small
                      color="success"
                      class="mr-2"
                      :disabled="!isEditable"
                      @click="editItem(item)"
                    >
                      mdi-pencil
                    </v-icon>
                    <v-icon
                      small
                      color="error"
                      :disabled="!isEditable"
                      @click="deleteItem(item)"
                    >
                      mdi-delete
                    </v-icon>
                  </div>
                  <v-select
                    v-else
                    :items="subjectItem"
                    :menu-props="{ top: false, offsetY: true }"
                    item-text="subjectName"
                    item-value="subjectName"
                    v-model="item[header.value]"
                    hide-details
                    :disabled="!isEditable"
                  >
                  </v-select>
                </td>
              </tr>
            </tbody>
          </template>
          <template v-slot:no-data>
            <p>暂无</p>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import {
  getScheduleClass,
  createScheduleClass,
  updateScheduleClass
} from "~/api/teacherSubject";
import lang from "~/helper/lang.json";
import { mapGetters } from "vuex";
export default {
  components: {},

  data: () => ({
    lang,
    dialog: false,
    dialogDelete: false,
    time: null,
    startTimeMenu: false,
    endTimeMenu: false,
    isEditable: false,
    subjectItem: [],
    headers: [
      { text: "节次", value: "ord", sortable: false, align: "left" },
      { text: "星期一", value: "mon", sortable: false, align: "left" },
      { text: "星期二", value: "tue", sortable: false },
      { text: "星期三", value: "wed", sortable: false },
      { text: "星期四", value: "thu", sortable: false },
      { text: "星期五", value: "fri", sortable: false },
      { text: "星期六", value: "sat", sortable: false },
      { text: "星期日", value: "sun", sortable: false }
    ],
    sessionName: "",
    scheduleData: [],
    schoolListRaw: [],

    editedIndex: -1,
    editedItem: {
      ord: "",
      mon: "",
      tue: "",
      wed: "",
      thu: "",
      fri: "",
      sat: "",
      sun: ""
    },
    defaultItem: {
      ord: "",
      mon: "",
      tue: "",
      wed: "",
      thu: "",
      fri: "",
      sat: "",
      sun: ""
    },
    subjectOrderItem: [
      "第一节",
      "第二节",
      "第三节",
      "第四节",
      "第五节",
      "第六节",
      "第七节"
    ],
    baseUrl: window.Laravel.base_url,
    isCreatingSchool: false,
    isLoadingSchoolData: false,
    isDeleteSchool: false,
    schoolIntroduceData: "",
    isCreatMode: false,
    isLoadingNewData: false,
    scheduleDataId: null
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "新增课" : "编辑课";
    },
    ...mapGetters({
      user: "auth/user"
    })
  },

  async created() {
    this.isLoadingSchoolData = true;
    await getScheduleClass()
      .then(res => {
        console.log("----res.data", res.data);
        this.sessionName = res.data.lastSession.sessionName;
        let selfStudy = {
          id: -1,
          subjectName: "自习"
        };
        this.subjectItem.push(selfStudy);
        res.data.scheduleTeacherDataArr.map(data => {
          data.subjectName = data.subjectName + " - " + data.teacherName;
          data.teacherAvatar = data.teacher.avatar;
          this.subjectItem.push(data);
        });
        if (res.data.scheduleData.length == 0) {
          this.isCreatMode = true;
          res.data.subjectOrder.map(order => {
            let scheduleDataItemObj = {
              ord: order.subjectOrderName,
              mon: "",
              tue: "",
              wed: "",
              thu: "",
              fri: "",
              sat: "",
              sun: ""
            };
            this.scheduleData.push(scheduleDataItemObj);
          });
        } else {
          this.scheduleDataId = res.data.scheduleData[0].id;
          let scheduleData = JSON.parse(res.data.scheduleData[0].scheduleData);
          res.data.subjectOrder.map((order, index) => {
            if (order.subjectOrderName == scheduleData[index].ord) {
              this.scheduleData.push(scheduleData[index]);
            } else {
              let scheduleDataItemObj = {
                ord: order.subjectOrderName,
                mon: "",
                tue: "",
                wed: "",
                thu: "",
                fri: "",
                sat: "",
                sun: ""
              };
              this.scheduleData.push(scheduleDataItemObj);
            }
          });
          // console.log("res.data.scheduleData", this.scheduleData);
          // console.log("this.scheduleData.length",this.scheduleData.length)
        }
      })
      .catch(err => {
        console.log(err.response);
      });
    this.isLoadingSchoolData = false;
  },

  watch: {
    dialog(val) {
      val || this.closeRow();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    }
  },

  methods: {
    showTime() {
      this.$router.push({ name: "admin.timeTableTest" });
    },
    editItem(item) {
      this.editedIndex = this.scheduleData.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.scheduleData.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      this.scheduleData.splice(this.editedIndex, 1);
      // let payload = {
      //     id : this.editedItem.id
      // }
      // this.isDeleteSchool = true;
      // await deleteSchool(payload)
      // .then((res) => {
      //     if(res.data.msg == 1){

      //     }
      //     this.isDeleteSchool = false;
      // }).catch((err) => {
      //     //console.log(err)
      //     this.isDeleteSchool = false;
      // });
      this.closeDelete();
    },

    closeRow() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    async saveRow() {
      //update scheduleData
      if (this.editedIndex > -1) {
        Object.assign(this.scheduleData[this.editedIndex], this.editedItem);
      }
      //saveRow scheduleData
      else {
        this.scheduleData.push(this.editedItem);
      }
      this.closeRow();
    },

    editable() {
      if (this.user.role.id == 5) {
        return;
      }
      this.isEditable = !this.isEditable;
    },

    //actions with cells
    save() {
      //console.log(this.classScheduleData)
    },
    cancel() {},
    open() {},
    close() {},
    async onSubmit() {
      this.isLoadingNewData = true;

      if (this.isCreatMode == true) {
        let payload = {
          classSchedule: this.scheduleData
        };
        console.log(payload);
        await createScheduleClass(payload)
          .then(res => {
            this.isEditable = false;
          })
          .catch(err => {
            this.isEditable = false;
          });
      } else {
        let payload = {
          classSchedule: this.scheduleData,
          id: this.scheduleDataId
        };
        await updateScheduleClass(payload)
          .then(res => {
            this.isEditable = false;
          })
          .catch(err => {
            this.isEditable = false;
          });
      }
      this.isLoadingNewData = false;
    }
  }
};
</script>
<style></style>
