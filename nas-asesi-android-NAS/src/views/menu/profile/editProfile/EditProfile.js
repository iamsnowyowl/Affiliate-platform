import React, { Component } from "react";
import {
  View,
  Text,
  Image,
  ScrollView,
  TouchableOpacity,
  Dimensions,
  Picker
} from "react-native";
import { connect } from "react-redux";
import { color } from "../../../../styles/color";
import Button from "../../../../components/button/button";
import Moment from "moment";
import DatePicker from "@react-native-community/datetimepicker";
import constants from "../../../../constants/constants";
import Header from "../../../../components/header/header";
import style from "./index";
import FormInput from "../../../../components/formInput/formInput";
import actions from "../../../../actions/";
import Icon from "react-native-vector-icons/MaterialCommunityIcons";
import RadioButton from "../../../../components/radioButton/radioButton";
import Dialog from "../../../../components/dialog/dialog";
import ImagePicker from "react-native-image-picker";
import LoadingBar from "../../../../components/loadingBar/loadingbar";
import Toast from "react-native-easy-toast";

const { width, height } = Dimensions.get("window");

class EditProfile extends Component {
  constructor(props) {
    super(props);
    this.state = {
      jobs: [],
      showdate: false,
      date: new Date(),
      onLoading: false,
      avatarSource: null,
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
      pendidikan_terakhir: this.props.user.pendidikan_terakhir
    };
  }

  setDate = (event, date) => {
    date = date || this.state.date;

    this.setState({
      date,
      date_of_birth: date,
      showdate: false
    });
  };

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);

    type == "jobs_name" && value != "" ? this.searchJobs(value) : null;
  };

  searchJobs(keyword) {
    this.props.getJobs(keyword, response => {
      this.setState({ jobs: response.data.data });
    });
  }

  _updateProfile() {
    this.modal._closeDialog();
    const data = {
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
    this.setState({ onLoading: true });
    this.props.updateProfile(
      false,
      this.props.auth.secret_key,
      this.props.upn,
      data,
      response => this._updateProfileCallback(response)
    );
  }

  _updateProfileCallback(response) {
    this.setState({ onLoading: false });
    if (response == "err") {
      this.refs.toast.show("Connection Error");
    } else {
      if (response.status == 200) {
        this.props.getUser(
          this.props.auth.secret_key,
          this.props.upn,
          null,
          response => this.props.navigation.goBack()
        );
      } else {
        this.refs.toast.show(response.data.error.message);
      }
    }
  }

  selectPhoto() {
    const options = {
      title: constants.MULTILANGUAGE(this.props.settings.bahasa).select_photo,
      takePhotoButtonTitle: constants.MULTILANGUAGE(this.props.settings.bahasa)
        .take_a_picture,
      chooseFromLibraryButtonTitle: constants.MULTILANGUAGE(
        this.props.settings.bahasa
      ).choose_from_library,
      cancelButtonTitle: constants.MULTILANGUAGE(this.props.settings.bahasa)
        .cancel,
      quality: 1.0,
      maxWidth: 500,
      maxHeight: 500,
      storageOptions: {
        skipBackup: true
      }
    };

    ImagePicker.showImagePicker(options, response => {
      if (response.didCancel) {
      } else {
        data = {
          image_b64: response.data
        };
        this.props.updateProfile(
          true,
          this.props.auth.secret_key,
          this.props.upn,
          data,
          response => this._updateProfileCallback(response)
        );
      }
    });
  }

  _selectedGender(gender) {
    this.setState({ gender_code: gender });
  }

  render() {
    return (
      <View style={{ flex: 1, backgroundColor: color.greyWhite }}>
        <Header
          leftIconType="icon"
          headerColor={color.green}
          rightIconName="close"
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).edit_profile
          }
          pageTitleColor="white"
          leftIconColor="white"
          onPressRightIcon={() => this.props.navigation.goBack()}
        />
        <ScrollView>
          <View
            style={{ paddingHorizontal: 20, paddingTop: 20, paddingBottom: 85 }}
          >
            <View
              style={{
                justifyContent: "center",
                alignSelf: "center"
              }}
            >
              <TouchableOpacity onPress={() => this.selectPhoto()}>
                <View style={style.round_image}>
                  <Image
                    style={{ width: 120, height: 120 }}
                    source={{
                      uri:
                        constants.URL +
                        this.props.user.picture +
                        "?timestamp=" +
                        Date.now()
                    }}
                  />
                </View>
              </TouchableOpacity>
              <View style={{ height: 15 }} />
              <Text style={style.title}>
                {this.props.user.first_name + " " + this.props.user.last_name}
              </Text>
              <Text style={style.subtitle}>{this.props.user.email}</Text>
            </View>
            <View
              style={{
                marginTop: 15,
                borderBottomColor: color.greyPlaceholder,
                borderBottomWidth: 1
              }}
            />
            <View>
              <View style={{ height: 15 }} />
              <FormInput
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
                ref="place_of_birth"
                type={"place_of_birth"}
                placeholder={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .place_of_birth
                }
                keyboardType={"default"}
                value={this.state.place_of_birth}
                onChangeText={(type, value) =>
                  this.onChangeText("place_of_birth", value)
                }
              />
              <View style={{ height: 15 }} />
              <Text style={{ marginBottom: 5, fontWeight: "bold" }}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .date_of_birth
                }
              </Text>
              <TouchableOpacity
                onPress={() => this.setState({ showdate: true })}
              >
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
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .gender_code
                }
              </Text>
              <View style={{ flexDirection: "row", marginLeft: 10 }}>
                <TouchableOpacity onPress={() => this._selectedGender("M")}>
                  <RadioButton
                    selected={this.state.gender_code == "M" ? true : false}
                    title={
                      constants.MULTILANGUAGE(this.props.settings.bahasa).male
                    }
                    color={color.green}
                    size={20}
                  />
                </TouchableOpacity>
                <View style={{ width: 15 }} />
                <TouchableOpacity onPress={() => this._selectedGender("F")}>
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
                  onValueChange={(item, index) =>
                    this.setState({ religion: item })
                  }
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
                ref="address"
                type={"address"}
                placeholder={
                  constants.MULTILANGUAGE(this.props.settings.bahasa).address
                }
                keyboardType={"default"}
                value={this.state.address}
                onChangeText={(type, value) =>
                  this.onChangeText("address", value)
                }
              />
              <View style={{ height: 15 }} />
              <FormInput
                ref="contact"
                type={"contact"}
                placeholder={
                  constants.MULTILANGUAGE(this.props.settings.bahasa).contact
                }
                keyboardType={"phone-pad"}
                value={this.state.contact}
                onChangeText={(type, value) =>
                  this.onChangeText("contact", value)
                }
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
                ref="institution"
                type={"institution"}
                placeholder={
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .institution
                }
                keyboardType={"default"}
                value={this.state.institution}
                onChangeText={(type, value) =>
                  this.onChangeText("institution", value)
                }
              />
              <View style={{ height: 15 }} />
              <FormInput
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
              {this.state.jobs.length > 0 ? (
                this.state.jobs.map((item, index) => {
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
              ) : (
                <View />
              )}

              <View style={{ height: 15 }} />
              <Text style={{ fontWeight: "bold" }}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .you_registerred_in
                }
              </Text>
              <Text style={style.disableText}>{this.props.user.tuk_name}</Text>
              <View style={{ height: 15 }} />
              <View style={{ flexDirection: "row" }}>
                <Button
                  title="add signature"
                  titleColor="white"
                  onPressed={() =>
                    this.props.navigation.navigate("SignatureCanvas")
                  }
                />
                <View style={{ width: 15 }} />
                <Text style={{ textAlignVertical: "center" }}>
                  {this.props.user.signature_flag == 1
                    ? "Sudah tanda tangan"
                    : "Belum tanda tangan"}
                </Text>
              </View>
            </View>
          </View>
        </ScrollView>
        <View style={{ position: "absolute", bottom: 0 }}>
          {/* <TouchableHighlight onPress={() => this.modal._openDialog()}>
            <View
              style={{
                width: width,
                height: 55,
                backgroundColor: color.green,
                justifyContent: 'center',
                alignItems: 'center'
              }}
            >
              <Text style={{ color: color.white, fontSize: 15 }}>
                {}
              </Text>
            </View>
          </TouchableHighlight> */}
          <View
            style={{
              width: width,
              position: "absolute",
              bottom: 20,
              flex: 1,
              paddingHorizontal: 25
            }}
          >
            <Button
              title={constants.MULTILANGUAGE(this.props.settings.bahasa).save}
              titleColor="white"
              onPressed={() => this.modal._openDialog()}
            />
          </View>
        </View>
        <Dialog
          title={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .save_dialog_title
          }
          description={
            constants.MULTILANGUAGE(this.props.settings.bahasa).save_dialog_desc
          }
          ref={action => (this.modal = action)}
        >
          <View>
            <View style={{ height: 20 }} />
            <View
              style={{
                flexDirection: "row",
                justifyContent: "flex-end"
              }}
            >
              <TouchableOpacity
                style={style.btn_in_modal}
                onPress={() => this.modal._closeDialog()}
              >
                <View
                  style={{
                    flex: 1,
                    justifyContent: "center",
                    alignSelf: "center"
                  }}
                >
                  <Text
                    style={{
                      fontWeight: "bold",
                      color: "white",
                      fontSize: 16
                    }}
                  >
                    {constants.MULTILANGUAGE(this.props.settings.bahasa).no}
                  </Text>
                </View>
              </TouchableOpacity>
              <View style={{ width: 10 }} />
              <TouchableOpacity
                style={style.btn_in_modal}
                onPress={() => this._updateProfile()}
              >
                <View
                  style={{
                    flex: 1,
                    justifyContent: "center",
                    alignSelf: "center"
                  }}
                >
                  <Text
                    style={{
                      fontWeight: "bold",
                      color: "white",
                      fontSize: 16
                    }}
                  >
                    {constants.MULTILANGUAGE(this.props.settings.bahasa).yes}
                  </Text>
                </View>
              </TouchableOpacity>
            </View>
          </View>
        </Dialog>
        <LoadingBar visibility={this.state.onLoading} />
        <Toast ref="toast" />
      </View>
    );
  }
}

const mapStateToProps = state => ({
  tuk: state.tuk,
  auth: state.auth,
  user: state.user,
  upn: state.upn,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  updateProfile: (picture, secret_key, username_email, data, callback) =>
    dispatch(
      actions.actionsAPI.user.updateProfile(
        picture,
        secret_key,
        username_email,
        data,
        callback
      )
    ),
  getUser: (secret_key, username_email, data, callback) =>
    dispatch(
      actions.actionsAPI.user.getUser(
        secret_key,
        username_email,
        data,
        callback
      )
    ),
  getJobs: (keyword, callback) =>
    dispatch(actions.actionsAPI.discover.getJobs(keyword, callback))
});

export default connect(mapStateToProps, mapDispatchToProps)(EditProfile);
