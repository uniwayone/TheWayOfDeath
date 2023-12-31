<template>
  <v-container class="pa-0">
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
          <h2>任课教师</h2>
        </v-col>
        <v-col cols="12" md="4" class="d-flex align-center justify-end">
          <v-btn
            color="#7879ff"
            dark
            tile
            class="mb-2"
            @click="dialog = !dialog"
            :disabled="!isEditable"
          >
            添加
          </v-btn>
          <v-btn
            color="#f19861"
            dark
            class="mb-2 ml-2"
            tile
            v-if="isEditable == false"
            @click="isEditable = !isEditable"
          >
            <v-icon left>
              mdi-check
            </v-icon>
            修改
          </v-btn>
          <v-btn
            color="#f19861 accent-4"
            dark
            class="mb-2 ml-2"
            tile
            v-if="isEditable == true"
            @click="isEditable = !isEditable"
          >
            <v-icon left>
              mdi-alert-circle-outline
            </v-icon>
            保存
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
    <v-dialog
      :overlay-opacity="$isMobile() ? '0' : '0.4'"
      v-model="dialog"
      persistent
      max-width="500px"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-select
                  solo
                  :items="subjectNameItem"
                  :menu-props="{ top: false, offsetY: true }"
                  item-text="subjectName"
                  item-value="id"
                  v-model="editedItem.scheduleSettingId"
                  label="课程名称"
                  @change="onChangeSubjectName"
                  return-object
                  hide-details
                ></v-select>
              </v-col>
              <v-col cols="12">
                <v-select
                  solo
                  :items="teacherNameItem"
                  :menu-props="{ top: false, offsetY: true }"
                  item-text="name"
                  item-value="id"
                  v-model="editedItem.teacherId"
                  label="任课教师"
                  @change="onChangeTeacherName"
                  return-object
                  hide-details
                ></v-select>
              </v-col>
              <v-col cols="12">
                <v-select
                  solo
                  multiple
                  :items="classItem"
                  :menu-props="{ top: false, offsetY: true }"
                  item-text="lessonName"
                  item-value="lessonId"
                  v-model="editedItem.lessons"
                  label="任课班级"
                  :disabled="editedItem.scheduleSettingId == null"
                  hide-details
                ></v-select>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">
            取消
          </v-btn>
          <v-btn color="blue darken-1" text :loading="isCreating" @click="save">
            保存
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      :overlay-opacity="$isMobile() ? '0' : '0.4'"
      persistent
      v-model="dialogDelete"
      max-width="500px"
    >
      <v-card>
        <v-card-title class="headline">{{ lang.confirmSentence }}</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="closeDelete">{{
            lang.cancel
          }}</v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="deleteItemConfirm"
            :loading="isDeleting"
            >{{ lang.ok }}</v-btn
          >
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <div class="px-10 mt-8">
      <v-data-table
        :headers="headers"
        :items="scheduleTeacherData"
        :loading="isLoadingSchoolData"
        loading-text="正在加载..."
        sort-by="calories"
        class="elevation-1"
      >
        <template v-slot:[`item.actions`]="{ item }">
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
        </template>
        <template v-slot:no-data>
          <p>暂无</p>
        </template>
      </v-data-table>
    </div>
  </v-container>
</template>

<script>
import { mapGetters } from "vuex";
import { getBaseData } from "~/api/managerSchedule";
import {
  createScheduleTeacher,
  updateScheduleTeacher,
  getScheduleTeacher,
  deleteScheduleTeacher
} from "~/api/managersubject";
import lang from "~/helper/lang.json";
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
    isGettingBaseData: false,
    currentSelectedGrade: null,
    teacherNameItem: [],
    subjectNameItem: [],
    classItem: [],
    headers: [
      { text: "课程名称", value: "subjectName", align: "start" },
      { text: "任课教师", value: "teacherName", sortable: false },
      { text: "任课班级", value: "lessons", sortable: false },
      { text: "操作", value: "actions", sortable: false }
    ],
    scheduleTeacherData: [],
    editedIndex: -1,
    editedItem: {
      scheduleSettingId: null,
      subjectName: "",
      teacherId: null,
      teacherName: "",
      lessons: []
    },
    defaultItem: {
      scheduleSettingId: null,
      subjectName: "",
      teacherId: null,
      teacherName: "",
      lessons: []
    },

    baseUrl: window.Laravel.base_url,
    isCreating: false,
    isLoadingSchoolData: false,
    isDeleting: false,
    schoolIntroduceData: "",
    mySchoolData: {}
  }),

  computed: {
    ...mapGetters({
      schoolData: "schooltree/schoolData",
      user: "auth/user"
    }),
    formTitle() {
      return this.editedIndex === -1 ? "新增课程安排" : "编辑课程安排";
    }
  },
  async created() {
    this.isLoadingSchoolData = true;
    this.isGettingBaseData = true;
    await getBaseData()
      .then(res => {
        this.initialized(res.data.data);
      })
      .catch(err => {
        //console.log(err.response)
      });
    this.isGettingBaseData = false;
    this.schoolData.map(x => {
      if (this.user.schoolId == x.id) {
        this.mySchoolData = x;
      }
    });

    //console.log(this.currentSelectedGrade)
    await getScheduleTeacher()
      .then(res => {
        this.scheduleTeacherData = res.data;
        //console.log(this.scheduleTeacherData)
      })
      .catch(err => {
        //console.log(err.response);
      });

    this.isLoadingSchoolData = false;
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    }
  },

  methods: {
    initialized(data) {
      data.subjectArr.map(sub => {
        this.subjectNameItem.push(sub);
      });
      data.teacherArr.map(user => {
        this.teacherNameItem.push(user);
      });
    },
    editItem(item) {
      //console.log('------',item)
      this.editedIndex = this.scheduleTeacherData.indexOf(item);
      this.editedItem = Object.assign({}, item);
      //console.log('+++++',this.editedItem)
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.scheduleTeacherData.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    async deleteItemConfirm() {
      this.isDeleting = true;
      await deleteScheduleTeacher({ id: this.editedIndex })
        .then(res => {
          this.scheduleTeacherData.splice(this.editedIndex, 1);
          //console.log(res)
          this.isDeleting = false;
        })
        .catch(err => {
          this.isDeleting = false;
          //console.log(err.response)
        });
      this.closeDelete();
    },

    close() {
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

    async save() {
      //update scheduleTeacherData
      this.isCreating = true;
      //console.log(this.editedItem)

      //console.log(this.editedIndex)
      if (this.editedIndex > -1) {
        await updateScheduleTeacher(this.editedItem)
          .then(res => {
            Object.assign(
              this.scheduleTeacherData[this.editedIndex],
              this.editedItem
            );
          })
          .catch(err => {
            this.isCreating = false;
            //console.log(err.response)
          });
      }
      //save scheduleTeacherData
      else {
        await createScheduleTeacher(this.editedItem)
          .then(res => {
            // let index = this.subjectNameItem.findIndex(sbj=> sbj.value === this.editedItem.subject)
            // if(index > -1){
            //     this.subjectNameItem.splice(index,1)
            // }
            this.scheduleTeacherData.push(res.data);
          })
          .catch(err => {
            this.isCreating = false;
            //console.log(err.response);
          });
      }
      this.isCreating = false;
      this.close();
    },

    triggerSubject(data) {
      this.subjectNameItem = [];
      data.map(sub => {
        let element = {
          label: "",
          value: ""
        };
        element.label = sub.subjectName;
        element.value = sub.id;
        this.subjectNameItem.push(element);
      });
    },

    onChangeSubjectName(val) {
      console.log(val);
      this.editedItem.scheduleSettingId = val.id;
      this.editedItem.subjectName = val.subjectName;
      this.editedItem.lessons = [];
      // this.classItem
      this.mySchoolData.grades.map(grade => {
        if (grade.id == val.gradeId) {
          this.classItem = grade.lessons;
        }
      });
    },
    onChangeTeacherName(val) {
      this.editedItem.teacherId = val.id;
      this.editedItem.teacherName = val.name;
    }
  }
};
</script>
