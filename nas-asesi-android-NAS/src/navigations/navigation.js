import React, { Component } from "react";
import { createStackNavigator, createAppContainer } from "react-navigation";
import { View, Text, Alert, Dimensions, TouchableOpacity } from "react-native";
import { connect } from "react-redux";
import { StackActions, NavigationActions } from "react-navigation";
import { color } from "../styles/color";
// import firebase from 'react-native-firebase';
import NetInfo from "@react-native-community/netinfo";
import AsyncStorage from "@react-native-community/async-storage";

import constants from "../constants/constants";

import Splashscreen from "../views/auth/splashscreen/splashscreen";
import StepWizard from "../views/auth/splashscreen/StepWizard";
import Login from "../views/auth/login/Login";
import Register from "../views/auth/register/Register";
import ForgotPassword from "../views/auth/forgotPassword/ForgotPassword";
import Menu from "../views/menu/index";
import Notifications from "../views/menu/notifications/Notifications";

import CertificationDetail from "../views/menu/discover/certificationDetail/certificationDetail";
import ScheduleList from "../views/menu/discover/scheduleList/scheduleList";
import ChooseAsesi from "../views/menu/discover/chooseAsesi/chooseAsesi";

import OrderReview from "../views/menu/cart/orderReview/orderReview";
import InvoiceDetail from "../views/menu/cart/invoiceDetail/InvoiceDetail";
import TransactionDetail from "../views/menu/cart/transactionDetail/TransactionDetail";

import NewsUpdates from "../views/menu/home/newsUpdates/newsUpdates";
import DetailNews from "../views/menu/home/newsUpdates/detailNews/detailNews";

import Schedules from "../views/menu/home/schedules/schedules";
import DetailAssessment from "../views/menu/home/assessment/detailAssessment/DetailAssessment";

import Assessment from "../views/menu/home/assessment/Assessment";
import JoinSchedule from "../views/menu/home/schedules/joinSchedule/JoinSchedule";

import CertificationScheme from "../views/menu/home/certificationScheme/certificationScheme";
import JoinScheme from "../views/menu/home/certificationScheme/joinScheme/joinScheme";

import Help from "../views/menu/home/help/help";
import WebviewPage from "../components/webview/webview";

import EditProfile from "../views/menu/profile/editProfile/EditProfile";
import SignatureCanvas from "../views/menu/profile/editProfile/SignatureCanvas";
import ChangeLanguage from "../views/menu/profile/change_language/ChangeLanguage";
import ContactUs from "../views/menu/profile/contact_us/contact_us";

import actions from "../actions";
import axios from "axios";
import Dialog from "../components/dialog/dialog";
import LinearGradient from "react-native-linear-gradient";

const { width, height } = Dimensions.get("window");

class Application extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isConnected: true
    };
    axios.defaults.timeout = 10000;
  }
  async componentDidMount() {
    NetInfo.isConnected.addEventListener(
      "connectionChange",
      this.handleConnectivityChange
    );
    this._interceptor();
    this.checkUPN();
    // this._checkPermission();
    // this.createNotificationListeners();
  }

  // async createNotificationListeners() {
  //   /*
  //    * Triggered when a particular notification has been received in foreground
  //    * */
  //   this.notificationListener = firebase
  //     .notifications()
  //     .onNotification(notification => {
  //       const { title, body } = notification;
  //       this.showAlert(title, body);
  //     });

  //   /*
  //    * If your app is in background, you can listen for when a notification is clicked / tapped / opened as follows:
  //    * */
  //   this.notificationOpenedListener = firebase
  //     .notifications()
  //     .onNotificationOpened(notificationOpen => {
  //       const { title, body } = notificationOpen.notification;
  //       this.showAlert(title, body);
  //     });

  //   /*
  //    * If your app is closed, you can check if it was opened by a notification being clicked / tapped / opened as follows:
  //    * */
  //   const notificationOpen = await firebase
  //     .notifications()
  //     .getInitialNotification();
  //   if (notificationOpen) {
  //     const { title, body } = notificationOpen.notification;
  //     this.showAlert(title, body);
  //   }
  //   /*
  //    * Triggered for data only payload in foreground
  //    * */
  //   this.messageListener = firebase.messaging().onMessage(message => {
  //     //process data message
  //     console.log(JSON.stringify(message));
  //   });
  // }

  checkUPN = async () => {
    const upn = await AsyncStorage.getItem(constants.SECRET_KEY);
    if (upn != null) {
      setTimeout(() => {
        this.props.upn == "" ? this.modal._openDialog() : null;
      }, 1000);
    }
  };

  showAlert(title, body) {
    Alert.alert(
      title,
      body,
      [{ text: "OK", onPress: () => console.log("OK Pressed") }],
      { cancelable: false }
    );
  }

  _interceptor = () => {
    return axios.interceptors.response.use(
      response => {
        return response;
      },
      error => {
        return error.response.status == 401
          ? this.modal._openDialog()
          : error.response.status == 419
          ? this.modalExpired._openDialog()
          : Promise.reject(error);
      }
    );
  };

  logout = async () => {
    this.modal._closeDialog();
    await AsyncStorage.clear();
    this.navigator._navigation.dispatch(
      StackActions.reset({
        index: 0,
        actions: [
          NavigationActions.navigate({
            routeName: "Login"
          })
        ]
      })
    );
  };

  handleConnectivityChange = isConnected => {
    if (isConnected) {
      this.setState({ isConnected });
    } else {
      this.setState({ isConnected });
    }
  };

  // _checkPermission = async () => {
  //   let fcmEnabled = await firebase.messaging().hasPermission();
  //   if (fcmEnabled) {
  //     await firebase.messaging().getToken();
  //   } else {
  //     this._requestFcmPermission();
  //   }
  // };

  // _requestFcmPermission = async () => {
  //   try {
  //     await firebase.messaging().requestPermission();
  //   } catch (error) {
  //     // console.log('permission rejected');
  //   }
  // };

  componentWillUnmount() {
    NetInfo.isConnected.removeEventListener(
      "connectionChange",
      this.handleConnectivityChange
    );
    // this.notificationListener();
    // this.notificationOpenedListener();
  }

  render() {
    return (
      <View style={{ flex: 1 }}>
        {!this.state.isConnected ? (
          <View
            style={{
              position: "absolute",
              top: 0,
              elevation: 10,
              width: width,
              height: 20,
              backgroundColor: "red"
            }}
          >
            <Text
              style={{
                fontWeight: "bold",
                alignSelf: "center",
                color: "white",
                fontSize: 16
              }}
            >
              {
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .connection_lost
              }
            </Text>
          </View>
        ) : null}
        <AppContainer
          ref={nav => {
            this.navigator = nav;
          }}
        />
        {/* modal apabila login session sudah habis */}
        <Dialog
          title={constants.MULTILANGUAGE(this.props.settings.bahasa).oops}
          description={
            constants.MULTILANGUAGE(this.props.settings.bahasa).session_expired
          }
          dialogCanClose={false}
          ref={action => (this.modal = action)}
        >
          <TouchableOpacity onPress={() => this.logout()}>
            <LinearGradient
              start={{ x: 0, y: 0 }}
              end={{ x: 1, y: 0 }}
              colors={[color.green, color.lightGreen]}
              style={{
                borderRadius: 5,
                backgroundColor: color.green,
                height: 40,
                justifyContent: "center"
              }}
            >
              <Text
                style={{
                  color: color.white,
                  fontWeight: "bold",
                  fontSize: 18,
                  justifyContent: "center",
                  alignSelf: "center"
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).ok}
              </Text>
            </LinearGradient>
          </TouchableOpacity>
        </Dialog>
        {/* modal apabila freetrial session sudah habis */}
        <Dialog
          title="Oops!"
          description="Masa free trial anda sudah habis, silakan hubungi Customer Services NAS."
          dialogCanClose={false}
          ref={action => (this.modalExpired = action)}
        >
          <TouchableOpacity onPress={() => this.modalExpired._closeDialog()}>
            <LinearGradient
              start={{ x: 0, y: 0 }}
              end={{ x: 1, y: 0 }}
              colors={[color.green, color.lightGreen]}
              style={{
                borderRadius: 5,
                backgroundColor: color.green,
                height: 40,
                justifyContent: "center"
              }}
            >
              <Text
                style={{
                  color: color.white,
                  fontWeight: "bold",
                  fontSize: 18,
                  justifyContent: "center",
                  alignSelf: "center"
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).ok}
              </Text>
            </LinearGradient>
          </TouchableOpacity>
        </Dialog>
      </View>
    );
  }
}

const AppContainer = createAppContainer(
  createStackNavigator(
    {
      Splashscreen: { screen: Splashscreen },
      StepWizard: { screen: StepWizard },
      Login: { screen: Login },
      Register: { screen: Register },
      ForgotPassword: { screen: ForgotPassword },

      Menu: { screen: Menu },
      Notifications: { screen: Notifications },
      CertificationDetail: { screen: CertificationDetail },
      ScheduleList: { screen: ScheduleList },
      ChooseAsesi: { screen: ChooseAsesi },

      OrderReview: { screen: OrderReview },
      InvoiceDetail: { screen: InvoiceDetail },
      TransactionDetail: { screen: TransactionDetail },

      NewsUpdates: { screen: NewsUpdates },
      DetailNews: { screen: DetailNews },

      Schedules: { screen: Schedules },
      DetailAssessment: { screen: DetailAssessment },

      Assessment: { screen: Assessment },
      JoinSchedule: { screen: JoinSchedule },

      CertificationScheme: { screen: CertificationScheme },
      JoinScheme: { screen: JoinScheme },

      Help: { screen: Help },
      WebviewPage: { screen: WebviewPage },

      EditProfile: { screen: EditProfile },
      SignatureCanvas: { screen: SignatureCanvas },
      ChangeLanguage: { screen: ChangeLanguage },

      ContactUs: { screen: ContactUs }
    },
    {
      headerMode: "none",
      initialRouteName: "Splashscreen"
    }
  )
);

const mapStateToProps = state => ({
  user: state.user,
  upn: state.upn,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  login: (data, callback) =>
    dispatch(actions.actionsAPI.auth.login(data, callback)),
  fcmToken: data => dispatch(actions.actionsAPI.settings.fcmToken(data))
});

export default connect(mapStateToProps, mapDispatchToProps)(Application);
