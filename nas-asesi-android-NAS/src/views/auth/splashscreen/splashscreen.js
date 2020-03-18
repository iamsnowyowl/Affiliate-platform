import React, { Component } from "react";
import {
  Text,
  Image,
  View,
  Dimensions,
  NativeModules,
  TouchableOpacity,
  ActivityIndicator
} from "react-native";
import { StackActions, NavigationActions } from "react-navigation";
import { color } from "../../../styles/color";
import { connect } from "react-redux";
import styles from "./styles";
import constants from "../../../constants/constants";
import actions from "../../../actions";
import AsyncStorage from "@react-native-community/async-storage";

const { width, height } = Dimensions.get("window");
const locale = NativeModules.I18nManager.localeIdentifier;

class SplashScreen extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isSuccess: false
    };
  }

  componentDidMount() {
    this._setLanguage();
    this._getTuks();
  }

  _getTuks = () => {
    this.setState({ isSuccess: true });
    this.props.getTUKs(null, response => {
      this.setState({ isSuccess: false });
      if (response.data) {
        let { responseStatus } = response.data;
        if (responseStatus == "SUCCESS") {
          // this._authenticationCheck();
          !this.props.settings.first_time
            ? this._authenticationCheck()
            : this._stepWizard();
        }
      } else {
        this.setState({ isSuccess: false });
      }
    });
  };

  _setLanguage = () => {
    this.props.gantiBahasa(locale == "en_US" ? "en" : "id");
  };

  //cek token disini
  _authenticationCheck = async () => {
    const userToken = await AsyncStorage.getItem(constants.SECRET_KEY);
    this.props.navigation.dispatch(
      StackActions.reset({
        index: 0,
        actions: [
          NavigationActions.navigate({
            routeName: userToken ? "Menu" : "Login"
          })
        ]
      })
    );
  };

  _stepWizard = () => {
    this.props.navigation.dispatch(
      StackActions.reset({
        index: 0,
        actions: [
          NavigationActions.navigate({
            routeName: "StepWizard"
          })
        ]
      })
    );
  };

  render() {
    return (
      <View
        style={{
          flex: 1,
          justifyContent: "center",
          backgroundColor: "#f6fff5"
        }}
      >
        <Image
          style={styles.bigIcon}
          source={require("../../../assets/image/nas_landscape.png")}
        />
        <View style={{ height: 30 }} />
        {/* <Text style={styles.bigTitle}>S E R T I M E D I A</Text> */}
        {this.state.isSuccess ? (
          <ActivityIndicator
            color={color.green}
            animating={true}
            size={"large"}
          />
        ) : (
          <View>
            <Text style={styles.errorText}>
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .error_and_tryagain
              }
            </Text>
            <TouchableOpacity onPress={() => this._getTuks()}>
              <Text style={styles.tryAgain}>
                {constants.MULTILANGUAGE(this.props.settings.bahasa).try_again}
              </Text>
            </TouchableOpacity>
          </View>
        )}
      </View>
    );
  }
}

const mapStateToProps = state => ({
  settings: state.settings,
  tuk: state.tuk
});

const mapDispatchToProps = dispatch => ({
  getTUKs: (data, callback) =>
    dispatch(actions.actionsAPI.discover.getTUKs(data, callback)),
  gantiBahasa: (data, callback) =>
    dispatch(actions.actionsAPI.settings.gantiBahasa(data, callback))
});

export default connect(mapStateToProps, mapDispatchToProps)(SplashScreen);
