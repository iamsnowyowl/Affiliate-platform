import React, { Component } from "react";
import {
  View,
  Platform,
  Animated,
  Dimensions,
  StyleSheet,
  Text,
  ScrollView,
  RefreshControl,
  TouchableOpacity
} from "react-native";
import { connect } from "react-redux";
import { color } from "../../../../styles/color";
import SearchForm from "../../../../components/formWithIcon/formWithIcon";
import Moment from "moment";
import LoadingBar from "../../../../components/loadingBar/loadingbar";
import globalStyle from "../../../../styles/index";
import actions from "../../../../actions";
import EmptyContainer from "../../../../components/emptyContainer/emptyContainer";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";

const { width, height } = Dimensions.get("window");
class Schedules extends Component {
  constructor(props) {
    super(props);
    this.state = {
      refreshing: false,
      search: "",
      visible: false,
      position: 1,
      assessments: []
    };
    this.moveAnimation = new Animated.ValueXY({ x: 0, y: 0 });
  }

  componentDidMount() {
    this._search("");
  }

  onChangeText = (type, value) => {
    let state = this.state;
    state[type] = value;
    setTimeout(() => {
      this._search(value);
    }, 1000);
    this.setState(state);
  };

  _search = keyword => {
    this.setState({ visible: true });
    let tuk_id = this.props.user.tuk_id;
    this.props.getAvailableAssessments(
      this.props.auth.secret_key,
      this.props.upn,
      keyword,
      tuk_id,
      response =>
        this.setState({ assessments: response.data.data, visible: false })
    );
  };

  _showSearchBar = () => {
    if (this.state.position == 1) {
      this.setState({ position: 2 });
      Animated.spring(this.moveAnimation, {
        toValue: { x: 0, y: Platform.OS == "ios" ? 80 : 50 }
      }).start();
    } else {
      this.setState({ position: 1 });
      Animated.spring(this.moveAnimation, {
        toValue: { x: 0, y: 0 }
      }).start();
    }
  };

  _refreshControl() {
    return (
      <RefreshControl
        refreshing={this.state.refreshing}
        onRefresh={() => this._search("")}
        colors={[color.green, color.lightGreen]}
      />
    );
  }

  render() {
    return (
      <View style={{ flex: 1, backgroundColor: color.greyWhite }}>
        <Animated.View style={[styles.hide, this.moveAnimation.getLayout()]}>
          <SearchForm
            ref="search"
            type="search"
            value={this.state.search}
            keyboardType="default"
            placeholder={
              constants.MULTILANGUAGE(this.props.settings.bahasa).search
            }
            onChangeText={(type, value) => this.onChangeText("search", value)}
          />
        </Animated.View>
        <Header
          headerColor={color.green}
          leftIconName="arrow-left"
          leftIconColor="white"
          leftIconType="icon"
          rightIconName="search"
          pageTitleColor="white"
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).schedule
          }
          onPressLeftIcon={() => this.props.navigation.goBack()}
          onPressRightIcon={() => this._showSearchBar()}
        />
        {this.props.availableAssessments.length > 0 ? (
          <ScrollView
            refreshControl={this._refreshControl()}
            showsVerticalScrollIndicator={false}
            style={{
              alignSelf: "center",
              paddingTop: this.state.position == 1 ? 20 : 60,
              zIndex: 1
            }}
          >
            {this.props.availableAssessments.map((item, index) => {
              return (
                <TouchableOpacity
                  key={index}
                  style={{
                    marginBottom:
                      index == this.props.availableAssessments.length - 1
                        ? 40
                        : 15
                  }}
                  onPress={() =>
                    this.props.navigation.navigate("JoinSchedule", {
                      assessment_id: item.assessment_id
                    })
                  }
                >
                  <View style={styles.container}>
                    <View style={{ width: width - 60 }}>
                      <Text style={globalStyle.text(14, 5, "700", color.black)}>
                        {item.title}
                      </Text>
                      <Text
                        style={globalStyle.text(
                          12,
                          0,
                          "normal",
                          color.darkGrey
                        )}
                      >
                        {item.start_date}
                      </Text>
                      <View style={{ height: 8 }} />
                      <Text
                        style={globalStyle.text(
                          14,
                          0,
                          "normal",
                          color.darkGrey
                        )}
                      >
                        {item.schema_label}
                      </Text>
                    </View>
                  </View>
                </TouchableOpacity>
              );
            })}
          </ScrollView>
        ) : (
          <View
            style={{
              height: height - 120,
              justifyContent: "center"
            }}
          >
            <EmptyContainer
              emptyText={
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .empty_assessments
              }
            />
          </View>
        )}
        <LoadingBar visibility={this.state.visible} />
      </View>
    );
  }
}

const styles = StyleSheet.create({
  hide: {
    position: "absolute",
    width: width,
    zIndex: 2
  },
  container: {
    backgroundColor: color.white,
    flexDirection: "row",
    width: width - 40,
    elevation: 5,
    height: 100,
    borderRadius: 5,
    justifyContent: "center",
    alignItems: "center",
    position: "relative"
  }
});

const mapStateToProps = state => ({
  auth: state.auth,
  assessments: state.assessments,
  availableAssessments: state.availableAssessments,
  settings: state.settings,
  user: state.user,
  upn: state.upn
});

const mapDispatchToProps = dispatch => ({
  getAvailableAssessments: (secretkey, username, keyword, tuk_id, callback) =>
    dispatch(
      actions.actionsAPI.assessments.getAvailableAssessments(
        secretkey,
        username,
        keyword,
        tuk_id,
        callback
      )
    ),
  getSchema: (secret_key, username, callback) => {
    dispatch(
      actions.actionsAPI.discover.getSchema(secret_key, username, callback)
    );
  }
});

export default connect(mapStateToProps, mapDispatchToProps)(Schedules);
