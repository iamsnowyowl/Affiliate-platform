import React, { Component } from "react";
import {
  View,
  Platform,
  Animated,
  Dimensions,
  StyleSheet,
  ScrollView,
  RefreshControl,
  TouchableOpacity
} from "react-native";
import { connect } from "react-redux";
import { color } from "../../../../styles/color";
import SearchForm from "../../../../components/formWithIcon/formWithIcon";
import AssessmentItem from "../../../../components/rowItem/rowItem";
import actions from "../../../../actions";
import LoadingBar from "../../../../components/loadingBar/loadingbar";
import EmptyContainer from "../../../../components/emptyContainer/emptyContainer";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";

const { width, height } = Dimensions.get("window");
class Assessment extends Component {
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
    this.props.searchAssessments(
      this.props.auth.secret_key,
      this.props.upn,
      keyword,
      response => {
        this.setState({ visible: false });
        this.setState({ assessments: response.data.data });
      }
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
            constants.MULTILANGUAGE(this.props.settings.bahasa).assessment2
          }
          onPressLeftIcon={() => this.props.navigation.goBack()}
          onPressRightIcon={() => this._showSearchBar()}
        />
        {this.props.assessments.length > 0 ? (
          <ScrollView
            refreshControl={this._refreshControl()}
            style={{
              // padding: 20,
              paddingTop: this.state.position == 1 ? 20 : 50,
              zIndex: -1
            }}
          >
            {this.props.assessments.map((item, index) => {
              return (
                <TouchableOpacity
                  key={index}
                  style={{
                    marginBottom:
                      index == this.props.assessments.length - 1 ? 40 : 10,
                    margin: 15
                  }}
                  onPress={() =>
                    this.props.navigation.navigate("DetailAssessment", {
                      assessment_id: item.assessment_id
                    })
                  }
                >
                  <AssessmentItem
                    title={item.title}
                    description={item.address}
                    time={item.start_date}
                    status={
                      item.last_activity_state == "ON_REVIEW_APPLICANT_DOCUMENT"
                        ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .pra_assessment
                        : item.last_activity_state == "ON_COMPLETED_REPORT"
                        ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .pra_assessment_selesai
                        : item.last_activity_state == "REAL_ASSESSMENT"
                        ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .assessment
                        : item.last_activity_state == "PLENO_DOCUMENT_COMPLETED"
                        ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .pleno_assessment
                        : item.last_activity_state == "PLENO_REPORT_READY"
                        ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .pleno_assessment_selesai
                        : (item.last_activity_state == "PRINT_CERTIFICATE" &&
                            item.status_graduation == "L") ||
                          (item.last_activity_state == "PRINT_CERTIFICATE" &&
                            item.status_recomendation == "K")
                        ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .publish_certificate
                        : (item.last_activity_state == "PRINT_CERTIFICATE" &&
                            item.status_graduation == "TL") ||
                          (item.last_activity_state == "PRINT_CERTIFICATE" &&
                            item.status_recomendation == "BK") ||
                          item.last_activity_state == "COMPLETED"
                        ? constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .assessment_done
                        : constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .soon
                    }
                  />
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
    zIndex: 1
  }
});

const mapStateToProps = state => ({
  auth: state.auth,
  assessments: state.assessments,
  settings: state.settings,
  upn: state.upn
});

const mapDispatchToProps = dispatch => ({
  searchAssessments: (secretkey, username, data, callback) =>
    dispatch(
      actions.actionsAPI.assessments.searchAssessments(
        secretkey,
        username,
        data,
        callback
      )
    )
});

export default connect(mapStateToProps, mapDispatchToProps)(Assessment);
