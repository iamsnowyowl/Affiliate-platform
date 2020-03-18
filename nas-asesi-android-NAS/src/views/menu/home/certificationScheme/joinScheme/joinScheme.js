import React, { Component } from "react";
import { connect } from "react-redux";
import { color } from "../../../../../styles/color";
import {
  View,
  Text,
  Image,
  ScrollView,
  StyleSheet,
  Picker,
  TouchableOpacity
} from "react-native";
import Moment from "moment";
import style from "../index";
import Icon from "react-native-vector-icons/MaterialCommunityIcons";
import DocumentPicker from "react-native-document-picker";
import ImagePicker from "react-native-image-picker";
import RNFS from "react-native-fs";
import actions from "../../../../../actions";
import DatePicker from "@react-native-community/datetimepicker";
import Modal from "react-native-modal";
import RadioButton from "../../../../../components/radioButton/radioButton";
import constants from "../../../../../constants/constants";
import Dialog from "../../../../../components/dialog/dialog";
import BottomSheet from "../../../../../components/bottomSheet/bottomSheet";
import Header from "../../../../../components/header/header";
import Button from "../../../../../components/button/button";
import FormInput from "../../../../../components/formInput/formInput";
import Toast from "react-native-easy-toast";
import LoadingBar from "../../../../../components/loadingBar/loadingbar";

class JoinScheme extends Component {
  constructor(props) {
    super(props);
    this.state = {
      jobs: [],
      imagePath: "",
      visible: false,
      showdate: false,
      date: new Date(),
      showImage: false,
      request_status: "S",
      unitCompetencies: [],
      persyaratan_umum: [],
      nik: this.props.user.nik,
      tuk_id: this.props.user.tuk_id,
      address: this.props.user.address,
      contact: this.props.user.contact,
      religion: this.props.user.religion,
      jobs_code: this.props.user.jobs_code,
      jobs_name: this.props.user.jobs_name,
      // kebangsaan: this.props.user.kebangsaan,
      institution: this.props.user.institution,
      gender_code: this.props.user.gender_code,
      date_of_birth: this.props.user.date_of_birth,
      place_of_birth: this.props.user.place_of_birth,
      schema: this.props.navigation.getParam("schema"),
      pendidikan_terakhir: this.props.user.pendidikan_terakhir
    };
  }

  componentDidMount() {
    this.unitCompetences(this.state.schema.sub_schema_number);
    this.persyaratanUmum();
    this.getprofile();
  }

  getprofile() {
    this.props.getUser(
      this.props.auth.secret_key,
      this.props.upn,
      null,
      response => response
    );
  }

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);

    type == "jobs_name" && value != "" ? this.searchJobs(value) : null;
  };

  setDate = (event, date) => {
    date = date || this.state.date;

    this.setState({
      date,
      date_of_birth: date,
      showdate: false
    });
  };

  searchJobs(keyword) {
    this.props.getJobs(keyword, response => {
      this.setState({ jobs: response.data.data });
    });
  }

  unitCompetences = sub_schema_number => {
    this.setState({ visible: true });
    this.props.unitCompetences(
      this.props.auth.secret_key,
      this.props.upn,
      sub_schema_number,
      response => {
        this.setState({ unitCompetencies: response.data.data, visible: false });
      }
    );
  };

  persyaratanUmum() {
    let data = {
      secret_key: this.props.auth.secret_key,
      username_email: this.props.upn
    };
    this.props.getPersyaratanUmum(data, response => {
      if (response.status == 200) {
        this.setState({ persyaratan_umum: response.data.data });
      }
    });
  }

  joinRequest() {
    this.setState({ visible: true });
    let data = {
      applicant_id: this.props.user.user_id,
      sub_schema_number: this.state.schema.sub_schema_number,
      request_status: this.state.request_status
    };
    this.props.joinRequest(
      this.props.auth.secret_key,
      this.props.upn,
      data,
      response => {
        this.setState({ visible: false });
        this.updateProfile();
        response.status == 201 || response.status == 200
          ? this.props.navigation.goBack()
          : response.status == 409
          ? this.refs.toast.show("Anda sudah pernah mengajukan skema ini")
          : this.refs.toast.show(
              "Gagal mengajukan skema, silahkan coba kembali"
            );
      }
    );
  }

  updateProfile() {
    let data = {
      nik: this.state.nik,
      address: this.state.address,
      contact: this.state.contact,
      religion: this.state.religion,
      jobs_code: this.state.jobs_code,
      // kebangsaan: this.state.kebangsaan,
      institution: this.state.institution,
      gender_code: this.state.gender_code,
      pendidikan_terakhir: this.state.pendidikan_terakhir,
      date_of_birth: Moment(this.state.date_of_birth).format("YYYY-MM-DD"),
      place_of_birth: this.state.place_of_birth
    };
    this.props.updateProfile(
      false,
      this.props.auth.secret_key,
      this.props.upn,
      data,
      response => response
    );
  }

  selectedGender(gender) {
    this.setState({ gender_code: gender });
  }

  selectedStatus(status) {
    this.setState({ request_status: status });
  }

  toggleImage() {
    this.setState({ showImage: !this.state.showImage });
  }

  showImage() {
    return (
      <Modal
        isVisible={this.state.showImage}
        onBackdropPress={this.toggleImage.bind(this)}
        onBackButtonPress={this.toggleImage.bind(this)}
        animationIn="zoomInDown"
        animationOut="zoomOutDown"
        animationInTiming={600}
        animationOutTiming={600}
        backdropTransitionInTiming={600}
        backdropTransitionOutTiming={600}
        style={{ justifyContent: "center" }}
      >
        <View
          style={{
            backgroundColor: "white",
            borderRadius: 10,
            paddingTop: 10
          }}
        >
          <Image
            style={{
              width: 300,
              height: 300,
              borderRadius: 12,
              overflow: "hidden",
              alignSelf: "center"
            }}
            source={{ uri: constants.URL + this.state.imagePath }}
          />
        </View>
      </Modal>
    );
  }

  selectPhoto(master_portfolio_id) {
    const options = {
      quality: 1.0,
      maxWidth: 500,
      maxHeight: 500,
      storageOptions: {
        skipBackup: true
      }
    };
    ImagePicker.launchCamera(options, response => {
      this.upload_dialog._closeDialog();
      if (response.error) {
        this.refs.toast.show(response.error);
      } else if (response.didCancel) {
      } else {
        this.setState({ visible: true });
        let post = {
          master_portfolio_id: master_portfolio_id,
          form_value: response.data,
          filename: response.fileName
        };
        this.props.postPersyaratanUmum(
          this.props.auth.secret_key,
          this.props.upn,
          post,
          response => {
            this.setState({ visible: false });
            if (response.status == 201) {
              constants.MULTILANGUAGE(this.props.settings.bahasa).success +
                " upload portfolio";
              this.persyaratanUmum();
            } else {
              this.refs.toast.show(
                constants.MULTILANGUAGE(this.props.settings.bahasa).failed +
                  " upload portofolio"
              );
            }
          }
        );
      }
    });
  }

  pickFile = async master_portfolio_id => {
    this.upload_dialog._closeDialog();
    try {
      const res = await DocumentPicker.pick({
        type: [DocumentPicker.types.allFiles]
      });
      res.size < 5500000
        ? RNFS.readFile(res.uri, "base64")
            .then(file => {
              let post = {
                master_portfolio_id: master_portfolio_id,
                form_value: file,
                filename: res.name
              };
              this.setState({ visible: true });
              this.props.postPersyaratanUmum(
                this.props.auth.secret_key,
                this.props.upn,
                post,
                response => {
                  this.setState({ visible: false });
                  if (response.status == 201) {
                    constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .success + " upload portfolio";
                    this.persyaratanUmum();
                  } else {
                    this.refs.toast.show(
                      constants.MULTILANGUAGE(this.props.settings.bahasa)
                        .failed + " upload portofolio"
                    );
                  }
                }
              );
            })
            .catch(err => {
              this.setState({ visible: false });
            })
        : this.refs.toast.show(
            constants.MULTILANGUAGE(this.props.settings.bahasa).size_too_large
          );
    } catch (error) {
      if (DocumentPicker.isCancel(error)) {
        this.setState({ visible: false });
        // User cancelled the picker, exit any dialogs or menus and move on
      } else {
        this.setState({ visible: false });
        throw error;
      }
    }
  };

  deletePersyaratan(persyaratan_id) {
    this.setState({ visible: true });
    this.props.deletePersyaratan(
      this.props.auth.secret_key,
      this.props.upn,
      persyaratan_id,
      response => {
        this.setState({ visible: false });
        response.status == 200
          ? this.persyaratanUmum()
          : this.refs.toast.show("Gagal hapus file");
      }
    );
  }

  chooseFileDialog() {
    return (
      <Dialog
        ref={action => (this.upload_dialog = action)}
        title={constants.MULTILANGUAGE(this.props.settings.bahasa).select_photo}
      >
        <TouchableOpacity
          onPress={() => this.selectPhoto(this.state.master_portfolio_id)}
          style={{ height: 40, width: 150 }}
        >
          <Text style={{ color: "black" }}>
            {constants.MULTILANGUAGE(this.props.settings.bahasa).take_a_picture}
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          onPress={() => this.pickFile(this.state.master_portfolio_id)}
          style={{ height: 40, width: 150 }}
        >
          <Text style={{ color: "black" }}>
            {
              constants.MULTILANGUAGE(this.props.settings.bahasa)
                .choose_from_library
            }
          </Text>
        </TouchableOpacity>
        <View style={{ height: 10 }} />
        <TouchableOpacity onPress={() => this.upload_dialog._closeDialog()}>
          <Text style={{ textAlign: "right", paddingRight: 15 }}>
            {constants.MULTILANGUAGE(this.props.settings.bahasa).cancel}
          </Text>
        </TouchableOpacity>
      </Dialog>
    );
  }

  userProfile() {
    return (
      <BottomSheet
        ref={action => (this.bottom_sheet = action)}
        title={
          constants.MULTILANGUAGE(this.props.settings.bahasa).complete_your_data
        }
        description=""
      >
        <View>
          <FormInput
            required={true}
            ref="nik"
            type={"nik"}
            placeholder={
              constants.MULTILANGUAGE(this.props.settings.bahasa).nik
            }
            keyboardType={"number-pad"}
            value={this.state.nik}
            onChangeText={(type, value) => this.onChangeText("nik", value)}
          />
          <View style={{ height: 15 }} />
          <FormInput
            required={true}
            ref="place_of_birth"
            type={"place_of_birth"}
            placeholder={
              constants.MULTILANGUAGE(this.props.settings.bahasa).place_of_birth
            }
            keyboardType={"default"}
            value={this.state.place_of_birth}
            onChangeText={(type, value) =>
              this.onChangeText("place_of_birth", value)
            }
          />
          <View style={{ height: 15 }} />
          <Text style={{ marginBottom: 5, fontWeight: "bold" }}>
            {constants.MULTILANGUAGE(this.props.settings.bahasa).date_of_birth}
          </Text>
          <TouchableOpacity onPress={() => this.setState({ showdate: true })}>
            <View style={{ flexDirection: "row" }}>
              <Text style={style.dateBox}>
                {Moment(this.state.date_of_birth).format("DD MMMM YYYY")}
              </Text>
              <Icon
                style={{ paddingLeft: 15, paddingVertical: 7 }}
                name="calendar"
                color={color.darkGrey}
                size={35}
              />
            </View>
          </TouchableOpacity>
          {this.state.showdate && (
            <DatePicker
              value={this.state.date}
              mode="date"
              onChange={this.setDate}
            />
          )}
          <View style={{ height: 15 }} />
          <Text style={{ marginBottom: 5, fontWeight: "bold" }}>
            {constants.MULTILANGUAGE(this.props.settings.bahasa).gender_code}
          </Text>
          <View style={{ flexDirection: "row", marginLeft: 10 }}>
            <TouchableOpacity onPress={() => this.selectedGender("M")}>
              <RadioButton
                selected={this.state.gender_code == "M" ? true : false}
                title={constants.MULTILANGUAGE(this.props.settings.bahasa).male}
                color={color.green}
                size={20}
              />
            </TouchableOpacity>
            <View style={{ width: 15 }} />
            <TouchableOpacity onPress={() => this.selectedGender("F")}>
              <RadioButton
                selected={this.state.gender_code == "F" ? true : false}
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa).female
                }
                color={color.green}
                size={20}
              />
            </TouchableOpacity>
          </View>
          <View style={{ height: 15 }} />
          <Text style={{ fontWeight: "bold" }}>
            {constants.MULTILANGUAGE(this.props.settings.bahasa).religion}
          </Text>
          <View
            style={{
              height: 45,
              color: "black",
              borderWidth: 1,
              borderRadius: 5,
              borderColor: color.darkGrey,
              backgroundColor: "white"
            }}
          >
            <Picker
              style={{ height: 43 }}
              selectedValue={this.state.religion}
              onValueChange={(item, index) => this.setState({ religion: item })}
            >
              <Picker.Item label="" value="" />
              {constants.AGAMA.map((item, index) => {
                return (
                  <Picker.Item
                    key={index}
                    label={item.nama_agama}
                    value={item.nama_agama}
                  />
                );
              })}
            </Picker>
          </View>
          <View style={{ height: 15 }} />
          <FormInput
            required={true}
            ref="address"
            type={"address"}
            placeholder={
              constants.MULTILANGUAGE(this.props.settings.bahasa).address
            }
            keyboardType={"default"}
            value={this.state.address}
            onChangeText={(type, value) => this.onChangeText("address", value)}
          />
          <View style={{ height: 15 }} />
          <FormInput
            required={true}
            ref="contact"
            type={"contact"}
            placeholder={
              constants.MULTILANGUAGE(this.props.settings.bahasa).contact
            }
            keyboardType={"phone-pad"}
            value={this.state.contact}
            onChangeText={(type, value) => this.onChangeText("contact", value)}
          />
          <View style={{ height: 15 }} />
          <Text style={{ fontWeight: "bold" }}>
            {
              constants.MULTILANGUAGE(this.props.settings.bahasa)
                .pendidikan_terakhir
            }
          </Text>
          <View
            style={{
              height: 45,
              color: "black",
              borderWidth: 1,
              borderRadius: 5,
              borderColor: color.darkGrey,
              backgroundColor: "white"
            }}
          >
            <Picker
              style={{ height: 43 }}
              selectedValue={this.state.pendidikan_terakhir}
              onValueChange={(item, index) =>
                this.setState({ pendidikan_terakhir: item })
              }
            >
              <Picker.Item label="" value="" />
              {constants.PENDIDIKAN.map((item, index) => {
                return (
                  <Picker.Item
                    key={index}
                    label={item.nama_pendidikan}
                    value={item.nama_pendidikan}
                  />
                );
              })}
            </Picker>
          </View>
          <View style={{ height: 15 }} />
          {/* data pekerjaan */}
          <FormInput
            required={true}
            ref="institution"
            type={"institution"}
            placeholder={
              constants.MULTILANGUAGE(this.props.settings.bahasa).institution
            }
            keyboardType={"default"}
            value={this.state.institution}
            onChangeText={(type, value) =>
              this.onChangeText("institution", value)
            }
          />
          <View style={{ height: 15 }} />
          <FormInput
            required={true}
            ref="jobs_name"
            type={"jobs_name"}
            placeholder={
              constants.MULTILANGUAGE(this.props.settings.bahasa).job
            }
            keyboardType={"default"}
            value={this.state.jobs_name}
            onChangeText={(type, value) =>
              this.onChangeText("jobs_name", value)
            }
          />
          {this.state.jobs.length > 0
            ? this.state.jobs.map((item, index) => {
                return (
                  <View
                    key={index}
                    style={{
                      backgroundColor: "white",
                      borderLeftWidth: 1,
                      borderRightWidth: 1,
                      borderBottomWidth: 1,
                      borderColor: color.greyPlaceholder,
                      borderBottomRightRadius:
                        index == this.state.jobs.length - 1 ? 8 : 0,
                      borderBottomLeftRadius:
                        index == this.state.jobs.length - 1 ? 8 : 0
                    }}
                  >
                    <TouchableOpacity
                      onPress={() =>
                        this.setState({
                          jobs_name: item.jobs_name,
                          jobs_code: item.jobs_code,
                          jobs: []
                        })
                      }
                    >
                      <Text
                        style={{
                          paddingVertical: 10,
                          paddingHorizontal: 5
                        }}
                      >
                        {item.jobs_name}
                      </Text>
                    </TouchableOpacity>
                  </View>
                );
              })
            : null}
          <View style={{ height: 15 }} />
          {/* array persyaratan umum */}
          <Text
            style={{
              color: color.black,
              fontSize: 14,
              fontWeight: "bold",
              marginBottom: 10
            }}
          >
            {
              constants.MULTILANGUAGE(this.props.settings.bahasa)
                .file_requirement
            }
          </Text>
          {this.state.persyaratan_umum.length > 0
            ? this.state.persyaratan_umum.map((item, index) => {
                return (
                  <View key={index} style={{ marginBottom: 10 }}>
                    <Text>
                      {item.form_name}
                      <Text style={{ color: "red" }}>*maks. 5mb</Text>
                    </Text>
                    <TouchableOpacity
                      onPress={() => {
                        this.setState({
                          master_portfolio_id: item.master_portfolio_id
                        });
                        this.upload_dialog._openDialog();
                      }}
                      style={styles.btn}
                    >
                      <Text style={{ color: color.black }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .upload_image
                        }
                      </Text>
                    </TouchableOpacity>
                    {item.persyaratan.length > 0
                      ? item.persyaratan.map((item2, index2) => {
                          return (
                            <View
                              key={index2}
                              style={{
                                marginTop: 5,
                                paddingBottom: 5,
                                borderBottomWidth: 1,
                                borderBottomColor: color.darkGrey
                              }}
                            >
                              {item.form_type == "file" &&
                              item2.form_value != "" ? (
                                <View>
                                  <Text
                                    style={{
                                      fontSize: 12,
                                      color: "black",
                                      textAlign: "right"
                                    }}
                                  >
                                    {item2.filename}
                                  </Text>
                                  <View
                                    style={{
                                      flexDirection: "row",
                                      justifyContent: "space-between"
                                    }}
                                  >
                                    <TouchableOpacity
                                      onPress={() =>
                                        this.deletePersyaratan(
                                          item2.persyaratan_umum_id
                                        )
                                      }
                                      style={styles.btnDelete}
                                    >
                                      <Text style={{ color: "red" }}>
                                        Delete
                                      </Text>
                                    </TouchableOpacity>
                                    <View style={{ width: 5 }} />
                                    {item2.ext == "jpg" ||
                                    item2.ext == "jpeg" ||
                                    item2.ext == "png" ? (
                                      <TouchableOpacity
                                        onPress={() =>
                                          this.setState(
                                            {
                                              selectedImg: item2.form_value
                                            },
                                            () =>
                                              this.setState({
                                                showImage: true
                                              })
                                          )
                                        }
                                        style={styles.btnShowImg}
                                      >
                                        <Text style={{ color: "white" }}>
                                          {
                                            constants.MULTILANGUAGE(
                                              this.props.settings.bahasa
                                            ).show_image
                                          }
                                        </Text>
                                      </TouchableOpacity>
                                    ) : item2.ext == "pdf" ? (
                                      <TouchableOpacity
                                        onPress={() => {
                                          this.bottom_sheet.close(),
                                            this.props.navigation.navigate(
                                              "WebviewPage",
                                              {
                                                pageName: "Preview",
                                                url: `https://docs.google.com/gview?embedded=true&url=${constants.URL}${item2.form_value}`
                                              }
                                            );
                                        }}
                                        style={styles.btnShowImg}
                                      >
                                        <Text style={{ color: "white" }}>
                                          Lihat
                                        </Text>
                                      </TouchableOpacity>
                                    ) : null}
                                  </View>
                                </View>
                              ) : null}
                            </View>
                          );
                        })
                      : null}
                    <View
                      style={{
                        marginTop: 10,
                        borderBottomWidth: 1,
                        borderBottomColor: color.darkGrey
                      }}
                    />
                  </View>
                );
              })
            : null}
          <View style={{ height: 15 }} />
          <Text style={{ marginBottom: 5, fontWeight: "bold" }}>
            {
              constants.MULTILANGUAGE(this.props.settings.bahasa)
                .type_submission
            }
            <Text style={{ color: "red" }}>*</Text>
          </Text>
          <View style={{ flexDirection: "row", marginLeft: 10 }}>
            <TouchableOpacity onPress={() => this.selectedStatus("S")}>
              <RadioButton
                selected={this.state.request_status == "S" ? true : false}
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .new_certification
                }
                color={color.green}
                size={20}
              />
            </TouchableOpacity>
            <View style={{ width: 15 }} />
            <TouchableOpacity onPress={() => this.selectedStatus("SU")}>
              <RadioButton
                selected={this.state.request_status == "SU" ? true : false}
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .recertification
                }
                color={color.green}
                size={20}
              />
            </TouchableOpacity>
          </View>
          <View style={{ height: 25 }} />
          <Button
            onPressed={() => {
              this.bottom_sheet.close();
              this.dialog._openDialog();
            }}
            title={constants.MULTILANGUAGE(this.props.settings.bahasa).request}
            titleColor="white"
            titleSize={14}
          />
        </View>
      </BottomSheet>
    );
  }

  validateProfile() {
    const {
      nik,
      jobs_code,
      jobs_name,
      address,
      contact,
      religion,
      // kebangsaan,
      institution,
      gender_code,
      date_of_birth,
      place_of_birth,
      pendidikan_terakhir
    } = this.state;

    if (
      nik == "" ||
      jobs_code == "" ||
      jobs_name == "" ||
      address == "" ||
      contact == "" ||
      religion == "" ||
      institution == "" ||
      gender_code == "" ||
      date_of_birth == "" ||
      place_of_birth == "" ||
      pendidikan_terakhir == ""
    ) {
      this.dialog._closeDialog();
      this.refs.toast.show("Data profil anda belum lengkap");
    } else {
      this.dialog._closeDialog();
      this.joinRequest();
    }
  }

  render() {
    const { schema } = this.state;
    return (
      <View style={{ flex: 1, backgroundColor: color.white }}>
        <Header
          headerColor={color.green}
          leftIconName="arrow-left"
          leftIconColor="white"
          leftIconType="icon"
          pageTitleColor="white"
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).schema_label
          }
          onPressLeftIcon={() => this.props.navigation.goBack()}
        />
        <ScrollView>
          <View style={{ flex: 1, paddingHorizontal: 20, paddingTop: 15 }}>
            <Text style={styles.title}>
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .schema_number
              }
            </Text>
            <Text style={styles.desc}>{schema.sub_schema_number}</Text>
            <View style={styles.line(false)} />
            {/* ----------------------------------------------------------- */}
            <Text style={styles.title}>
              {constants.MULTILANGUAGE(this.props.settings.bahasa).schema_name}
            </Text>
            <Text style={styles.desc}>{schema.schema_name}</Text>
            <View style={styles.line(false)} />
            {/* ----------------------------------------------------------- */}
            <Text style={styles.title}>
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .sub_schema_name
              }
            </Text>
            <Text style={styles.desc}>{schema.sub_schema_name}</Text>
            <View style={styles.line(false)} />
            {/* ----------------------------------------------------------- */}
            {this.state.unitCompetencies.length > 0 ? (
              <View>
                <Text style={styles.title}>
                  {
                    constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .competence_unit
                  }
                </Text>
                {this.state.unitCompetencies.map((item, index) => {
                  return (
                    <View
                      key={index}
                      style={{ flexDirection: "row", marginBottom: 5 }}
                    >
                      <Text style={{ marginHorizontal: 8 }}>-</Text>
                      <Text style={styles.desc}>{item.title}</Text>
                    </View>
                  );
                })}
                <View style={styles.line(false)} />
              </View>
            ) : (
              <View />
            )}
            {/* ---------------------------------------------------------- */}
          </View>
        </ScrollView>
        <View style={{ paddingHorizontal: 20, paddingBottom: 20 }}>
          <Button
            onPressed={() => this.bottom_sheet.open()}
            title={
              constants.MULTILANGUAGE(this.props.settings.bahasa).request_schema
            }
            titleColor="white"
          />
        </View>
        <LoadingBar visibility={this.state.visible} />
        <Dialog
          ref={action => (this.dialog = action)}
          title={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .request_dialog_title
          }
          description={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .request_dialog_desc
          }
        >
          <View style={{ flexDirection: "row", alignSelf: "flex-end" }}>
            <Button
              onPressed={() => this.dialog._closeDialog()}
              title={constants.MULTILANGUAGE(this.props.settings.bahasa).cancel}
              titleColor="white"
            />
            <View style={{ width: 15 }} />
            <Button
              onPressed={() => this.validateProfile()}
              title={
                constants.MULTILANGUAGE(this.props.settings.bahasa).request
              }
              titleColor="white"
            />
          </View>
        </Dialog>
        {this.userProfile()}
        {this.chooseFileDialog()}
        {this.showImage()}
        <Toast ref="toast" />
      </View>
    );
  }
}

const styles = StyleSheet.create({
  title: {
    marginBottom: 5,
    fontWeight: "bold"
  },
  desc: {
    color: color.black,
    paddingRight: 10
  },
  line: end => ({
    marginTop: 20,
    borderBottomWidth: 1,
    marginBottom: end == true ? 0 : 20,
    borderBottomColor: color.greyPlaceholder
  }),
  btn: {
    flex: 1,
    borderWidth: 2,
    borderRadius: 5,
    margin: 5,
    marginTop: 10,
    justifyContent: "center",
    height: 30,
    alignItems: "center",
    borderColor: color.darkGrey
  },
  btnShowImg: {
    flex: 1,
    borderRadius: 5,
    margin: 5,
    marginTop: 10,
    justifyContent: "center",
    height: 30,
    alignItems: "center",
    backgroundColor: color.green
  },
  btnDelete: {
    flex: 1,
    borderRadius: 5,
    margin: 5,
    marginTop: 10,
    justifyContent: "center",
    height: 30,
    borderColor: "red",
    borderWidth: 2,
    alignItems: "center"
  },
  btnReupload: {
    flex: 1,
    borderRadius: 5,
    margin: 5,
    marginTop: 10,
    justifyContent: "center",
    height: 30,
    borderColor: color.green,
    borderWidth: 2,
    alignItems: "center"
  }
});

const mapStateToProps = state => ({
  auth: state.auth,
  schemas: state.schemas,
  settings: state.settings,
  user: state.user,
  upn: state.upn
});

const mapDispatchToProps = dispatch => ({
  getUser: (secret_key, username_email, data, callback) => {
    dispatch(
      actions.actionsAPI.user.getUser(
        secret_key,
        username_email,
        data,
        callback
      )
    );
  },
  getJobs: (keyword, callback) =>
    dispatch(actions.actionsAPI.discover.getJobs(keyword, callback)),
  updateProfile: (picture, secret_key, username_email, data, callback) => {
    dispatch(
      actions.actionsAPI.user.updateProfile(
        picture,
        secret_key,
        username_email,
        data,
        callback
      )
    );
  },
  unitCompetences: (secret_key, upn, sub_schema_number, callback) => {
    dispatch(
      actions.actionsAPI.discover.unitCompetences(
        secret_key,
        upn,
        sub_schema_number,
        callback
      )
    );
  },
  deletePersyaratan: (secret_key, username_email, persyaratan_id, callback) => {
    dispatch(
      actions.actionsAPI.discover.deletePersyaratanUmum(
        secret_key,
        username_email,
        persyaratan_id,
        callback
      )
    );
  },
  joinRequest: (secret_key, upn, data, callback) => {
    dispatch(
      actions.actionsAPI.assessments.joinRequest(
        secret_key,
        upn,
        data,
        callback
      )
    );
  },
  getPersyaratanUmum: (data, callback) => {
    dispatch(actions.actionsAPI.discover.getPersyaratanUmum(data, callback));
  },
  postPersyaratanUmum: (secret_key, upn, data, callback) => {
    dispatch(
      actions.actionsAPI.discover.postPersyaratanUmum(
        secret_key,
        upn,
        data,
        callback
      )
    );
  }
});

export default connect(mapStateToProps, mapDispatchToProps)(JoinScheme);
