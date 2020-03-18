import React, { Component } from "react";
import {
  View,
  Text,
  Image,
  ScrollView,
  Dimensions,
  RefreshControl,
  TouchableOpacity
} from "react-native";
import { color } from "../../../styles/color";
import { connect } from "react-redux";
import AsesmenIcon from "../../../assets/image/asesmen.png";
import ScheduleIcon from "../../../assets/image/schedule.png";
import HelpIcon from "../../../assets/image/help.png";
import globalStyle from "../../../styles/index";
import Header from "../../../components/header/header";
import RowItem from "../../../components/rowItem/rowItem";
import EmptyContainer from "../../../components/emptyContainer/emptyContainer";
import NewsItem from "../../../components/newsItem/newsItem";
import constants from "../../../constants/constants";
import actions from "../../../actions";
import Dialog from "../../../components/dialog/dialog";
// import firebase from "react-native-firebase";

const { width, height } = Dimensions.get("screen");

class Home extends Component {
  static navigationOptions = {
    tabBarLabel: constants.MULTILANGUAGE("en").home
  };
  constructor(props) {
    super(props);
    this.state = {
      refreshing: false,
      secret_key: this.props.navigation.getParam(
        "secret_key",
        this.props.auth.secret_key
      ),
      username: this.props.upn,
      notifications: []
    };
    this.props.getArticles("", response => response);
    // this._getFCMToken();
  }

  componentDidMount() {
    this._getData();
    // this._getNotifications();
  }

  _getData = () => {
    this.props.getAssessments(
      this.state.secret_key,
      this.state.username,
      "",
      response => {
        this.props.getUser(
          this.state.secret_key,
          this.state.username,
          null,
          response => response
        );
      }
    );
  };

  // _getNotifications = () => {
  //   this.props.getNotification(
  //     this.state.secret_key,
  //     this.state.username,
  //     null,
  //     response => this.setState({ notifications: response.data.data })
  //   );
  // };

  // _getFCMToken = async () => {
  //   let token = await firebase.messaging().getToken();
  //   if (token != null) {
  //     this.props.fcmToken(this.state.secret_key, this.state.username, token);
  //   }
  // };

  // async writeStoragePermission() {
  //   try {
  //     const granted = await PermissionsAndroid.request(
  //       PermissionsAndroid.PERMISSIONS.WRITE_EXTERNAL_STORAGE
  //     );
  //     if (granted == PermissionsAndroid.RESULTS.GRANTED) {
  //       this._onDownload();
  //     } else {
  //       console.log('cannot save downloaded file');
  //     }
  //   } catch (error) {
  //     console.log('error', error);
  //   }
  // }

  // _onDownload() {
  //   DownloadManager.download(
  //     (url =
  //       'https://api-lspenergi-staging.djemana.com/files/assessment/origin/af728eee-2cf5-437e-b5f2-ac48aa3951d7/applicant_1551244887.xls'),
  //     (headers = {}),
  //     (config = {
  //       downloadTitle: 'Downloading File...',
  //       downloadDescription: 'Downloading Portfolio',
  //       saveAsName: 'applicant_1551244887.xls',
  //       allowedInRoaming: true,
  //       allowedInMetered: true,
  //       showInDownloads: true,
  //       external: true,
  //       path: 'Download/'
  //     })
  //   )
  //     .then(response => console.log('success', response))
  //     .catch(err => console.log('error', err));
  // }

  _refreshControl() {
    return (
      <RefreshControl
        refreshing={this.state.refreshing}
        onRefresh={() => this._getData()}
        colors={[color.green, color.lightGreen]}
      />
    );
  }

  render() {
    if (this.props.cart.gotoCart) {
      this.props.navigation.navigate("Cart");
    }
    return (
      <View style={{ height: height - 65, backgroundColor: color.greyWhite }}>
        <Header
          headerColor="white"
          title={constants.APP_NAME}
          rightIconName="bell"
          rightIconColor={color.darkGrey}
          onPressRightIcon={() =>
            this.props.navigation.navigate("Notifications")
          }
        />
        <ScrollView refreshControl={this._refreshControl()}>
          <View style={{ height: 15 }} />
          <View style={globalStyle.containerBackground("stretch")}>
            <View
              style={{
                width: width - 40,
                alignSelf: "center",
                justifyContent: "center"
              }}
            >
              <View
                style={{
                  alignContent: "center"
                }}
              >
                <TouchableOpacity
                  onPress={() =>
                    this.props.navigation.navigate("CertificationScheme")
                  }
                >
                  <View
                    style={{
                      elevation: 5,
                      borderRadius: 8,
                      paddingVertical: 15,
                      backgroundColor: "white",
                      flexDirection: "row",
                      paddingHorizontal: 15
                    }}
                  >
                    <Image
                      source={AsesmenIcon}
                      style={{ width: 60, height: 50 }}
                    />
                    <View style={{ marginLeft: 20 }}>
                      <Text style={{ fontWeight: "bold" }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .schema_label
                        }
                      </Text>
                      <View style={{ height: 5 }} />
                      <Text style={{ paddingRight: 80 }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .schema_desc
                        }
                      </Text>
                    </View>
                  </View>
                </TouchableOpacity>
                <View style={{ height: 15 }} />
                <TouchableOpacity
                  onPress={() => this.props.navigation.navigate("Schedules")}
                >
                  <View
                    style={{
                      elevation: 5,
                      borderRadius: 8,
                      paddingVertical: 15,
                      backgroundColor: "white",
                      flexDirection: "row",
                      paddingHorizontal: 15
                    }}
                  >
                    <Image
                      source={ScheduleIcon}
                      style={{ width: 60, height: 50 }}
                    />
                    <View style={{ marginLeft: 20 }}>
                      <Text style={{ fontWeight: "bold" }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .schedule
                        }
                      </Text>
                      <View style={{ height: 5 }} />
                      <Text style={{ paddingRight: 80 }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .schedule_desc
                        }
                      </Text>
                    </View>
                  </View>
                </TouchableOpacity>
                <View style={{ height: 15 }} />
                <TouchableOpacity
                  onPress={() => this.props.navigation.navigate("Help")}
                >
                  <View
                    style={{
                      elevation: 5,
                      borderRadius: 8,
                      paddingVertical: 15,
                      backgroundColor: "white",
                      flexDirection: "row",
                      paddingHorizontal: 15
                    }}
                  >
                    <Image
                      source={HelpIcon}
                      style={{ width: 60, height: 50 }}
                    />
                    <View style={{ marginLeft: 20 }}>
                      <Text style={{ fontWeight: "bold" }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .help
                        }
                      </Text>
                      <View style={{ height: 5 }} />
                      <Text style={{ paddingRight: 80 }}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .help_desc
                        }
                      </Text>
                    </View>
                  </View>
                </TouchableOpacity>
              </View>
            </View>
            <View style={{ height: 30 }} />
            <View style={globalStyle.containerBackground}>
              <View
                style={{
                  flexDirection: "row",
                  justifyContent: "space-between",
                  alignItems: "center",
                  marginBottom: 15
                }}
              >
                <Text style={globalStyle.text(18, 0, "bold", color.black)}>
                  {
                    constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .new_updates
                  }
                </Text>
                <TouchableOpacity
                  onPress={() => this.props.navigation.navigate("NewsUpdates")}
                >
                  <Text style={globalStyle.text(15, 0, "bold", color.green)}>
                    {
                      constants.MULTILANGUAGE(this.props.settings.bahasa)
                        .browse_all
                    }
                  </Text>
                </TouchableOpacity>
              </View>
              <ScrollView
                horizontal={true}
                alwaysBounceHorizontal={true}
                directionalLockEnabled={true}
                snapToInterval={305}
                snapToAlignment={"start"}
                showsHorizontalScrollIndicator={false}
                pagingEnabled={true}
                scrollEventThrottle={8}
              >
                {this.props.articles.length > 0 ? (
                  this.props.articles.map((item, index) => {
                    return (
                      <TouchableOpacity
                        key={index}
                        style={{ marginBottom: 20 }}
                        onPress={() =>
                          this.props.navigation.navigate("DetailNews", {
                            article_id: item.article_id
                          })
                        }
                      >
                        <NewsItem
                          isHorizontal={true}
                          newsImg={constants.URL + item.media}
                          newsTitle={item.title}
                          newsTime={item.created_date}
                        />
                      </TouchableOpacity>
                    );
                  })
                ) : (
                  <EmptyContainer
                    emptyText={
                      constants.MULTILANGUAGE(this.props.settings.bahasa)
                        .empty_news
                    }
                  />
                )}
              </ScrollView>
            </View>
            <View style={globalStyle.containerBackground}>
              <View
                style={{
                  flexDirection: "row",
                  justifyContent: "space-between",
                  alignItems: "center",
                  marginBottom: 15
                }}
              >
                <Text style={globalStyle.text(18, 0, "bold", color.black)}>
                  {
                    constants.MULTILANGUAGE(this.props.settings.bahasa)
                      .your_assessment
                  }
                </Text>
                <TouchableOpacity
                  onPress={() => this.props.navigation.navigate("Assessment")}
                >
                  <Text style={globalStyle.text(15, 0, "bold", color.green)}>
                    {constants.MULTILANGUAGE(this.props.settings.bahasa).more}
                  </Text>
                </TouchableOpacity>
              </View>
              <ScrollView>
                {this.props.assessments.length > 0 ? (
                  this.props.assessments.map((item, index) => {
                    return (
                      <TouchableOpacity
                        key={index}
                        style={{ marginBottom: 20 }}
                        onPress={() =>
                          this.props.navigation.navigate("DetailAssessment", {
                            assessment_id: item.assessment_id
                          })
                        }
                      >
                        <RowItem
                          title={item.title}
                          description={item.address}
                          time={item.start_date}
                          status={
                            item.last_activity_state ==
                            "ON_REVIEW_APPLICANT_DOCUMENT"
                              ? constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).pra_assessment
                              : item.last_activity_state ==
                                "ON_COMPLETED_REPORT"
                              ? constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).pra_assessment_selesai
                              : item.last_activity_state == "REAL_ASSESSMENT"
                              ? constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).assessment
                              : item.last_activity_state ==
                                "PLENO_DOCUMENT_COMPLETED"
                              ? constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).pleno_assessment
                              : item.last_activity_state == "PLENO_REPORT_READY"
                              ? constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).pleno_assessment_selesai
                              : (item.last_activity_state ==
                                  "PRINT_CERTIFICATE" &&
                                  item.status_graduation == "L") ||
                                (item.last_activity_state ==
                                  "PRINT_CERTIFICATE" &&
                                  item.status_recomendation == "K")
                              ? constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).publish_certificate
                              : (item.last_activity_state ==
                                  "PRINT_CERTIFICATE" &&
                                  item.status_graduation == "TL") ||
                                (item.last_activity_state ==
                                  "PRINT_CERTIFICATE" &&
                                  item.status_recomendation == "BK") ||
                                item.last_activity_state == "COMPLETED"
                              ? constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).assessment_done
                              : constants.MULTILANGUAGE(
                                  this.props.settings.bahasa
                                ).soon
                          }
                        />
                      </TouchableOpacity>
                    );
                  })
                ) : (
                  <EmptyContainer
                    emptyText={
                      constants.MULTILANGUAGE(this.props.settings.bahasa)
                        .empty_assessments
                    }
                  />
                )}
              </ScrollView>
            </View>
          </View>
        </ScrollView>
        <Dialog
          title={constants.MULTILANGUAGE(this.props.settings.bahasa).soon}
          description={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .not_available_now
          }
          ref={action => (this.modal = action)}
        >
          <TouchableOpacity onPress={() => this.modal._closeDialog()}>
            <View
              style={{
                borderRadius: 5,
                backgroundColor: color.green,
                height: 40,
                justifyContent: "center"
              }}
            >
              <Text
                style={{
                  color: "white",
                  alignSelf: "center",
                  fontSize: 18,
                  fontWeight: "bold"
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).ok}
              </Text>
            </View>
          </TouchableOpacity>
        </Dialog>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  assessments: state.assessments,
  articles: state.articles,
  auth: state.auth,
  cart: state.cart,
  upn: state.upn,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  getAssessments: (secretkey, username, data, callback) =>
    dispatch(
      actions.actionsAPI.assessments.getAssessments(
        secretkey,
        username,
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
  getArticles: (data, callback) =>
    dispatch(actions.actionsAPI.articles.listArticles(data, callback)),
  fcmToken: (secret_key, username_email, data) =>
    dispatch(
      actions.actionsAPI.settings.fcmToken(secret_key, username_email, data)
    ),
  getNotification: (secretkey, username, data, callback) =>
    dispatch(
      actions.actionsAPI.notifications.getNotification(
        secretkey,
        username,
        data,
        callback
      )
    )
});

export default connect(mapStateToProps, mapDispatchToProps)(Home);
