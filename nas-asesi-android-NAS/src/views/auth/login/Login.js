import React, { Component } from "react";
import { color } from "../../../styles/color";
import { StackActions, NavigationActions } from "react-navigation";
import { connect } from "react-redux";
import {
  View,
  Text,
  Image,
  TouchableOpacity,
  Keyboard,
  Dimensions,
  Animated
} from "react-native";
import FormInput from "../../../components/formInput/formInput";
import Button from "../../../components/button/button";
import globalStyle from "../../../styles/index";
import actions from "../../../actions";
import LoadingBar from "../../../components/loadingBar/loadingbar";
import constants from "../../../constants/constants";
import AppIcon from "../../../assets/image/nas_landscape.png";
import Header from "../../../assets/image/header.png";
import Icon from "react-native-vector-icons/FontAwesome";
import Toast from "react-native-easy-toast";

const { width, height } = Dimensions.get("window");

class Login extends Component {
  constructor(props) {
    super(props);
    this.state = {
      opacity: new Animated.Value(0),
      username: "",
      password: "",
      onLoading: false,
      showLogin: false
    };
  }

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);
  };

  _doLogin = () => {
    let { username, password } = this.state;
    Keyboard.dismiss();
    if (username == 0 || password == 0) {
      this.refs.toast.show(
        "username atau password " +
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
          username_email: username,
          password: password
        };
        this.setState({ onLoading: true });
        this.props.login(data, response => this._doLoginCallback(response));
      }
    }
  };

  _doLoginCallback(response) {
    this.setState({ onLoading: false });
    if (!response.data) {
      this.refs.toast.show("Connection Error");
    } else {
      if (response.status == 200) {
        if (response.data.data.role_code == "APL") {
          this.props.navigation.dispatch(
            StackActions.reset({
              index: 0,
              actions: [
                NavigationActions.navigate({
                  routeName: "Menu",
                  params: {
                    secret_key: response.data.secret_key,
                    username: response.data.data.username
                  }
                })
              ]
            })
          );
        } else {
          this.refs.toast.show(
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .not_registerred_as_assessee
          );
        }
      } else {
        this.refs.toast.show(response.data.error.message);
      }
    }
  }

  render() {
    return (
      <View
        style={{
          flex: 1,
          width: width,
          backgroundColor: "#f6fff5",
          alignItems: "center"
        }}
      >
        <Image
          source={AppIcon}
          style={{
            position: "absolute",
            top: 70,
            width: width - 50,
            height: 120
          }}
        />
        <View
          style={{
            height: height,
            width: width - 80,
            justifyContent: "center"
          }}
        >
          <View>
            <FormInput
              ref="username"
              type={"username"}
              keyboardType={"default"}
              placeholder={"Username atau Email"}
              value={this.state.username}
              onChangeText={(type, value) =>
                this.onChangeText("username", value)
              }
            />
            <View style={{ height: 10 }} />
            <FormInput
              ref="password"
              type={"password"}
              keyboardType={"default"}
              placeholder={
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .form_password
              }
              isPassword={true}
              value={this.state.password}
              onChangeText={(type, value) =>
                this.onChangeText("password", value)
              }
            />
            <View style={{ height: 25 }} />
            <Button
              onPressed={this._doLogin}
              title={constants.MULTILANGUAGE(this.props.settings.bahasa).login}
              titleSize={15}
              titleColor={"#fff"}
            />
          </View>
          <View style={{ height: 15 }} />
          <TouchableOpacity
            style={{ alignSelf: "flex-end" }}
            onPress={() => this.props.navigation.navigate("ForgotPassword")}
          >
            <Text style={globalStyle.text(16, 0, "normal", color.green)}>
              {constants.MULTILANGUAGE(this.props.settings.bahasa).forgotpass}
            </Text>
          </TouchableOpacity>
        </View>
        <View style={{ bottom: 70, alignSelf: "center" }}>
          <View style={{ height: 10 }} />
          <View style={{ flexDirection: "row" }}>
            <Text>
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .cannot_have_account
              }
            </Text>
            <TouchableOpacity
              onPress={() => this.props.navigation.navigate("Register")}
            >
              <Text
                style={{
                  color: color.green,
                  textAlign: "center"
                }}
              >
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .regist_here
                }
              </Text>
            </TouchableOpacity>
          </View>
        </View>
        <Toast ref="toast" />
        <LoadingBar visibility={this.state.onLoading} />
      </View>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  login: (data, callback) =>
    dispatch(actions.actionsAPI.auth.login(data, callback)),
  getUser: (secret_key, username_email, data, callback) =>
    dispatch(
      actions.actionsAPI.user.getUser(
        secret_key,
        username_email,
        data,
        callback
      )
    )
});

export default connect(mapStateToProps, mapDispatchToProps)(Login);
