import React, { Component } from "react";
import { View, Text, Keyboard, Image, ToastAndroid } from "react-native";
import { color } from "../../../styles/color";
import { connect } from "react-redux";
import actionsAPI from "../../../actions";
import globalStyle from "../../../styles";
import Button from "../../../components/button/button";
import Header from "../../../components/header/header";
import FormInput from "../../../components/formInput/formInput";
import actions from "../../../actions";
import Dialog from "../../../components/dialog/dialog";
import LoadingBar from "../../../components/loadingBar/loadingbar";
import constants from "../../../constants/constants";
import Toast from "react-native-easy-toast";

class ForgotPassword extends Component {
  constructor(props) {
    super(props);
    this.state = {
      email: "",
      onLoading: false
    };
  }

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);
  };

  _doForgotPassword = () => {
    let { email } = this.state;
    Keyboard.dismiss();
    if (email == 0) {
      this.refs.toast.show(
        "email " +
          constants.MULTILANGUAGE(this.props.settings.bahasa).cannot_empty
      );
    } else {
      let data = {
        email: email
      };
      this.setState({ onLoading: true });
      this.props.forgotPass(data, response =>
        this._forgotPassCallback(response)
      );
    }
  };

  _forgotPassCallback(response) {
    this.setState({ onLoading: false });
    if (response.status == 200) {
      this.setState({ onLoading: false });
      this.modal._openDialog();
    } else {
      this.setState({ onLoading: false });
      this.refs.toast.show(response.error.message);
    }
  }

  _goto() {
    this.modal._closeDialog();
    this.props.navigation.navigate("Login");
  }

  render() {
    return (
      <View style={globalStyle.containerBackground("stretch", color.green)}>
        <Header
          onPressLeftIcon={() => this.props.navigation.goBack()}
          leftIconType="icon"
          leftIconColor="#fff"
          leftIconName="arrow-left"
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).reset_password
          }
          pageTitleColor="#fff"
        />
        <View style={{ flex: 1 }}>
          <View style={{ marginTop: 20 }} />
          <Text style={globalStyle.text(25, 30, "bold", color.white)}>
            {constants.MULTILANGUAGE(this.props.settings.bahasa).reset_password}
          </Text>
          <Text style={globalStyle.text(16, 40, "normal", color.white)}>
            {
              constants.MULTILANGUAGE(this.props.settings.bahasa)
                .resetpasswordhint
            }
          </Text>
          <View style={globalStyle.cardContainer(color.white, 20, 40, 10)}>
            <FormInput
              ref="emailForgotPass"
              type={"emailForgotPass"}
              placeholder={"EMAIL"}
              keyboardType={"email-address"}
              onChangeText={(type, value) => this.onChangeText("email", value)}
            />
            <View style={{ height: 30 }} />
            <Button
              onPressed={() => this._doForgotPassword()}
              title={constants.MULTILANGUAGE(this.props.settings.bahasa).send}
              titleSize={15}
              titleColor={"#fff"}
            />
          </View>
          <View style={{ height: 80 }} />
          <View style={{ flex: 1, position: "relative" }}>
            <View
              style={{
                flexDirection: "row",
                justifyContent: "center",
                position: "absolute",
                alignSelf: "center",
                bottom: 25
              }}
            >
              <Image
                style={{ width: 30, height: 30, marginRight: 10 }}
                source={require("../../../assets/image/nas_logo.png")}
              />
              <Text
                style={{
                  fontSize: 16,
                  alignSelf: "center",
                  fontWeight: "bold",
                  color: color.white
                }}
              >
                NUSANTARA APLIKASI SERTIFIKASI
              </Text>
            </View>
          </View>
        </View>
        <Toast ref="toast" />
        <LoadingBar visibility={this.state.onLoading} />
        <Dialog
          ref={action => (this.modal = action)}
          title={constants.MULTILANGUAGE(this.props.settings.bahasa).success}
          description={
            constants.MULTILANGUAGE(this.props.settings.bahasa).check_email
          }
        >
          <View>
            <Button
              title={constants.MULTILANGUAGE(this.props.settings.bahasa).ok}
              titleColor={"white"}
              titleSize={16}
              onPressed={() => this._goto()}
            />
          </View>
        </Dialog>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  forgotPass: (data, callback) =>
    dispatch(actionsAPI.actionsAPI.auth.forgotPass(data, callback))
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(ForgotPassword);
