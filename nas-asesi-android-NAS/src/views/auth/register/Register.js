import React, { Component } from "react";
import {
  View,
  Text,
  ScrollView,
  Keyboard,
  Picker,
  Platform,
  TouchableOpacity
} from "react-native";
import { connect } from "react-redux";
import { color } from "../../../styles/color";
import globalStyle from "../../../styles";
import Button from "../../../components/button/button";
import Header from "../../../components/header/header";
import Dialog from "../../../components/dialog/dialog";
import FormInput from "../../../components/formInput/formInput";
import actions from "../../../actions";
import LoadingBar from "../../../components/loadingBar/loadingbar";
import RadioButton from "../../../components/radioButton/radioButton";
import constants from "../../../constants/constants";
import Toast from "react-native-easy-toast";

class Register extends Component {
  constructor(props) {
    super(props);
    this.state = {
      checked: false,
      onLoading: false,
      username: "",
      nik: "",
      first_name: "",
      last_name: "",
      contact: "",
      institution: "",
      email: "",
      gender_code: "",
      tuk_id: "",
      bahasa: "id"
    };
  }

  componentWillMount() {}

  componentDidMount() {
    this.setState({ renderList: false });
  }

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);
  };

  _doSignup = () => {
    let {
      username,
      nik,
      first_name,
      last_name,
      contact,
      email,
      institution,
      gender_code,
      tuk_id
    } = this.state;
    Keyboard.dismiss();
    if (
      username == 0 ||
      nik == 0 ||
      first_name == 0 ||
      contact == 0 ||
      institution == 0 ||
      email == 0 ||
      gender_code == 0 ||
      tuk_id == 0
    ) {
      this.refs.toast.show(
        constants.MULTILANGUAGE(this.props.settings.bahasa).cannot_empty
      );
    } else {
      if (/\s/.test(username)) {
        this.refs.toast.show(
          "username " +
            constants.MULTILANGUAGE(this.props.settings.bahasa).cannot_space
        );
      } else {
        let data = {
          username: username,
          nik: nik,
          first_name: first_name,
          last_name: last_name,
          contact: contact,
          institution: institution,
          email: email,
          gender_code: gender_code,
          tuk_id: tuk_id
        };
        this.setState({ onLoading: true });
        this.props.signup(data, response => this._signupCallback(response));
      }
    }
  };

  _selectedGender(gender) {
    this.setState({ gender_code: gender });
  }

  _signupCallback = response => {
    this.setState({ onLoading: false });
    if (response == "err") {
      this.refs.toast.show("Connection Error");
    } else {
      if (response.status == 200) {
        this.modal._openDialog();
      } else {
        this.refs.toast.show(response.data.error.message);
      }
    }
  };

  render() {
    return (
      <View style={{ backgroundColor: color.green }}>
        <Header
          onPressLeftIcon={() => this.props.navigation.goBack()}
          leftIconType="icon"
          leftIconColor="#fff"
          leftIconName="arrow-left"
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).register
          }
          pageTitleColor="#fff"
        />
        <ScrollView>
          <View
            style={{
              marginTop: 10,
              justifyContent: "center",
              paddingHorizontal: 20,
              marginBottom: 30
            }}
          >
            <Text style={globalStyle.text(25, 30, "bold", color.white)}>
              {constants.MULTILANGUAGE(this.props.settings.bahasa).hello}
            </Text>
            <Text style={globalStyle.text(16, 40, "normal", color.white)}>
              {constants.MULTILANGUAGE(this.props.settings.bahasa).registerhint}
            </Text>
            <View style={globalStyle.cardContainer(color.white, 20, 40, 10)}>
              <FormInput
                ref="username"
                type={"username"}
                placeholder={"USERNAME"}
                keyboardType={"default"}
                value={this.state.username}
                onChangeText={(type, value) =>
                  this.onChangeText("username", value)
                }
              />
              <View style={{ height: 5 }} />
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
              <View style={{ height: 5 }} />
              <FormInput
                ref="first_name"
                type={"first_name"}
                placeholder={
                  constants.MULTILANGUAGE(this.props.settings.bahasa).first_name
                }
                keyboardType={"default"}
                value={this.state.first_name}
                onChangeText={(type, value) =>
                  this.onChangeText("first_name", value)
                }
              />
              <View style={{ height: 5 }} />
              <FormInput
                ref="last_name"
                type={"last_name"}
                placeholder={
                  constants.MULTILANGUAGE(this.props.settings.bahasa).last_name
                }
                keyboardType={"default"}
                value={this.state.last_name}
                onChangeText={(type, value) =>
                  this.onChangeText("last_name", value)
                }
              />
              <View style={{ height: 5 }} />
              <FormInput
                ref="email"
                type={"email"}
                placeholder={"EMAIL"}
                keyboardType={"email-address"}
                value={this.state.email}
                onChangeText={(type, value) =>
                  this.onChangeText("email", value)
                }
              />
              <View style={{ height: 5 }} />
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
              <View style={{ height: 5 }} />
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
              <Text
                style={{
                  fontSize: 14,
                  fontWeight: "bold",
                  marginBottom: 10
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).gender}
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
              <View style={{ height: 20 }} />
              <View
                style={{
                  flex: 1,
                  borderWidth: 1,
                  borderRadius: 5,
                  borderColor: color.green
                }}
              >
                {Platform.OS == "ios" ? (
                  <TouchableOpacity
                    style={{
                      height: 40,
                      paddingHorizontal: 15,
                      justifyContent: "center"
                    }}
                  >
                    <Text>
                      {
                        constants.MULTILANGUAGE(this.props.settings.bahasa)
                          .choose_tuk
                      }
                    </Text>
                  </TouchableOpacity>
                ) : (
                  <Picker
                    selectedValue={this.state.tuk_id}
                    style={{ height: 50 }}
                    onValueChange={(itemValue, itemIndex) =>
                      this.setState({ tuk_id: itemValue })
                    }
                  >
                    <Picker.Item
                      label={
                        constants.MULTILANGUAGE(this.props.settings.bahasa)
                          .choose_tuk
                      }
                      value={null}
                    />
                    {this.props.tuk.length > 0 ? (
                      this.props.tuk.map((item, index) => {
                        return (
                          <Picker.Item
                            key={index}
                            label={item.tuk_name}
                            value={item.tuk_id}
                          />
                        );
                      })
                    ) : (
                      <Picker.Item
                        label={
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .no_tuk
                        }
                        value="null"
                      />
                    )}
                  </Picker>
                )}
              </View>
              <View style={{ height: 20 }} />
              <Button
                onPressed={this._doSignup}
                title={
                  constants.MULTILANGUAGE(this.props.settings.bahasa).register
                }
                titleSize={15}
                titleColor={"#fff"}
              />
            </View>
            <View style={{ height: 20 }} />
          </View>
        </ScrollView>
        <Dialog
          title={constants.MULTILANGUAGE(this.props.settings.bahasa).success}
          description={
            constants.MULTILANGUAGE(this.props.settings.bahasa).registerred
          }
          ref={action => (this.modal = action)}
        >
          <View>
            <View style={{ justifyContent: "center", alignSelf: "center" }}>
              <TouchableOpacity
                style={{
                  backgroundColor: color.green,
                  borderRadius: 5,
                  width: 120,
                  height: 40
                }}
                onPress={() => {
                  this.modal._closeDialog();
                  this.props.navigation.goBack();
                }}
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
                    {constants.MULTILANGUAGE(this.props.settings.bahasa).ok}
                  </Text>
                </View>
              </TouchableOpacity>
            </View>
          </View>
        </Dialog>
        <Toast ref="toast" />
        <LoadingBar visibility={this.state.onLoading} />
      </View>
    );
  }
}

const mapStateToProps = state => ({
  tuk: state.tuk,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  signup: (data, callback) =>
    dispatch(actions.actionsAPI.auth.signup(data, callback))
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Register);
