import React, { Component } from "react";
import {
  View,
  Text,
  TouchableOpacity,
  Image,
  Modal,
  Dimensions
} from "react-native";
import { connect } from "react-redux";
import { StackActions, NavigationActions } from "react-navigation";
import { color } from "../../../styles/color";
import style from "./index";
import actions from "../../../actions";
import constants from "../../../constants/constants";
import LoadingBar from "../../../components/loadingBar/loadingbar";
import Dialog from "../../../components/dialog/dialog";
import Header from "../../../components/header/header";
import Icon from "react-native-vector-icons/FontAwesome";
import AsyncStorage from "@react-native-community/async-storage";
const { width, height } = Dimensions.get("window");

class Profile extends Component {
  constructor(props) {
    super(props);
    this.state = {
      fullname: "",
      onLoading: false,
      detail_tuk: {}
    };
  }

  componentDidMount() {
    this._getFullname();
  }

  _gantiBahasa = bahasa => {
    this.props.gantiBahasa(bahasa, response => response);
  };

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    this.setState(state);
  };

  _logout = () => {
    this.props.logout(
      this.props.auth.secret_key,
      this.props.upn,
      null,
      response => {
        if (response.status == 200) {
          this.modal._closeDialog();
          this.setState({ onLoading: true });
          this._logoutAsync();
        }
      }
    );
  };

  _logoutAsync = async () => {
    await AsyncStorage.clear();
    this.setState({ onLoading: false });
    this.props.navigation.dispatch(
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

  _getFullname = () => {
    this.setState({
      fullname: this.props.user.first_name + " " + this.props.user.last_name
    });
  };

  static navigationOptions = {
    tabBarLabel: constants.MULTILANGUAGE("en").profile
  };
  render() {
    return (
      <View style={{ flex: 1, backgroundColor: color.greyWhite }}>
        <Header
          headerColor="white"
          title={constants.APP_NAME}
          onPressRightIcon={() => this.openDrawer()}
        />
        <View
          style={{
            paddingHorizontal: 20,
            backgroundColor: "white",
            paddingTop: 10
          }}
        >
          <View style={style.top_container}>
            <TouchableOpacity>
              <View style={style.round_image}>
                <Image
                  style={{ width: 80, height: 80 }}
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
            <View style={{ width: 15 }} />
            <View
              style={{
                flex: 1,
                justifyContent: "center"
              }}
            >
              <Text
                style={{
                  textAlign: "center",
                  color: color.green,
                  fontSize: 18,
                  fontWeight: "bold"
                }}
              >
                {this.state.fullname}
              </Text>
              <Text style={{ textAlign: "center", fontSize: 14 }}>
                {this.props.user.email}
              </Text>
              <TouchableOpacity
                onPress={() => this.props.navigation.navigate("EditProfile")}
              >
                <View
                  style={{
                    marginTop: 7,
                    backgroundColor: color.white,
                    borderWidth: 1,
                    borderRadius: 5,
                    borderColor: color.darkGrey,
                    height: 22,
                    justifyContent: "center",
                    alignItems: "center"
                  }}
                >
                  <Text
                    style={{
                      color: color.darkGrey
                    }}
                  >
                    {
                      constants.MULTILANGUAGE(this.props.settings.bahasa)
                        .edit_profile
                    }
                  </Text>
                </View>
              </TouchableOpacity>
            </View>
          </View>
          <TouchableOpacity
            onPress={() => this.props.navigation.navigate("ContactUs")}
          >
            <View
              style={{
                paddingVertical: 15,
                borderBottomWidth: 1,
                borderBottomColor: color.greyPlaceholder,
                flexDirection: "row"
              }}
            >
              <Icon name={"phone"} size={20} style={{ marginRight: 15 }} />
              <Text
                style={{
                  fontSize: 16,
                  justifyContent: "center"
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).contact_us}
              </Text>
              <View
                style={{
                  position: "absolute",
                  right: 0,
                  alignSelf: "center"
                }}
              >
                <Icon name={"angle-right"} size={20} />
              </View>
            </View>
          </TouchableOpacity>
          <TouchableOpacity
            onPress={() =>
              this.props.navigation.navigate("WebviewPage", {
                pageName: constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .term_condition,
                url: "https://aplikasisertifikasi.com/term-and-condition.html"
              })
            }
          >
            <View
              style={{
                paddingVertical: 15,
                borderBottomWidth: 1,
                borderBottomColor: color.greyPlaceholder,
                flexDirection: "row"
              }}
            >
              <Icon
                name={"check-square-o"}
                size={20}
                style={{ marginRight: 15 }}
              />
              <Text
                style={{
                  fontSize: 16,
                  justifyContent: "center"
                }}
              >
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .term_condition
                }
              </Text>
              <View
                style={{
                  position: "absolute",
                  right: 0,
                  alignSelf: "center"
                }}
              >
                <Icon name={"angle-right"} size={20} />
              </View>
            </View>
          </TouchableOpacity>
          <TouchableOpacity
            onPress={() =>
              this.props.navigation.navigate("WebviewPage", {
                pageName: constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .privacy_policies,
                url: "https://www.aplikasisertifikasi.com/privacy-policy/"
              })
            }
          >
            <View
              style={{
                paddingVertical: 15,
                borderBottomWidth: 1,
                borderBottomColor: color.greyPlaceholder,
                flexDirection: "row"
              }}
            >
              <Icon name={"shield"} size={20} style={{ marginRight: 15 }} />
              <Text
                style={{
                  fontSize: 16,
                  justifyContent: "center"
                }}
              >
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .privacy_policies
                }
              </Text>
              <View
                style={{
                  position: "absolute",
                  right: 0,
                  alignSelf: "center"
                }}
              >
                <Icon name={"angle-right"} size={20} />
              </View>
            </View>
          </TouchableOpacity>
          <TouchableOpacity
            onPress={() => this.props.navigation.navigate("ChangeLanguage")}
          >
            <View
              style={{
                paddingVertical: 15,
                borderBottomColor: color.greyPlaceholder,
                borderBottomWidth: 1
              }}
            >
              <View style={{ flexDirection: "row", marginBottom: 5 }}>
                <Icon name={"language"} size={20} style={{ marginRight: 15 }} />
                <Text style={{ fontSize: 16 }}>
                  {
                    constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .change_language
                  }
                </Text>
                <View
                  style={{
                    position: "absolute",
                    right: 0,
                    alignSelf: "center"
                  }}
                >
                  <Icon name={"angle-right"} size={20} />
                </View>
              </View>
            </View>
          </TouchableOpacity>
          <TouchableOpacity onPress={() => this.modal._openDialog()}>
            <View
              style={{
                paddingVertical: 15,
                borderBottomWidth: 1,
                borderBottomColor: color.greyPlaceholder,
                flexDirection: "row"
              }}
            >
              <Icon name={"sign-out"} size={20} style={{ marginRight: 15 }} />
              <Text
                style={{
                  fontSize: 16,
                  justifyContent: "center"
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).logout}
              </Text>
            </View>
          </TouchableOpacity>
        </View>
        <Dialog
          title={constants.MULTILANGUAGE(this.props.settings.bahasa).logout}
          description={
            constants.MULTILANGUAGE(this.props.settings.bahasa).logout_msg
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
                onPress={this._logout}
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
      </View>
    );
  }
}

const mapStateToProps = state => ({
  // tuk: state.tuk,
  auth: state.auth,
  user: state.user,
  upn: state.upn,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  logout: (secret_key, username_email, data, callback) =>
    dispatch(
      actions.actionsAPI.auth.logout(secret_key, username_email, data, callback)
    ),
  gantiBahasa: (data, callback) =>
    dispatch(actions.actionsAPI.settings.gantiBahasa(data, callback))
});

export default connect(mapStateToProps, mapDispatchToProps)(Profile);
