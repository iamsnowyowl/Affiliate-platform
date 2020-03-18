import React, { Component } from "react";
import { View, Text, ScrollView, StyleSheet } from "react-native";
import { connect } from "react-redux";
import { color } from "../../../../../styles/color";
import { NavigationActions } from "react-navigation";
import actions from "../../../../../actions";
import Header from "../../../../../components/header/header";
import constants from "../../../../../constants/constants";
import Moment from "moment";
import Button from "../../../../../components/button/button";
import LoadingBar from "../../../../../components/loadingBar/loadingbar";
import Toast from "react-native-easy-toast";

class JoinSchedule extends Component {
  constructor(props) {
    super(props);
    this.state = {
      assessment: {},
      show: false,
      showImage: false,
      selectedImg: "",
      check: "0",
      method: "",
      master_portfolio_id: "",
      assessment_id: this.props.navigation.getParam("assessment_id")
    };
  }

  componentDidMount() {
    let id = this.props.navigation.getParam("assessment_id");
    let detail = this.props.availableAssessments.find(function(element) {
      return element.assessment_id == id;
    });
    this.setState({ assessment: detail });
  }

  _joinSchedule(assessment_id) {
    this.setState({ show: true });
    this.props.joinAssessment(
      this.props.auth.secret_key,
      this.props.upn,
      assessment_id,
      response => {
        this.setState({ show: false });
        if (response == "409") {
          this.refs.toast.show(
            constants.MULTILANGUAGE(this.props.settings.bahasa).was_registered
          );
        } else if (response.status == 201) {
          this.refs.toast.show(
            constants.MULTILANGUAGE(this.props.settings.bahasa).success_join
          );
          this.props.navigation.reset(
            [
              NavigationActions.navigate({
                routeName: "Menu"
              })
            ],
            0
          );
        } else {
          this.refs.toast.show(
            constants.MULTILANGUAGE(this.props.settings.bahasa).failed_join
          );
        }
      }
    );
  }

  render() {
    const { assessment } = this.state;
    return (
      <View style={{ flex: 1, paddingBottom: 10, position: "relative" }}>
        <Header
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa)
              .detail_assessment
          }
          headerColor={color.green}
          leftIconType={"icon"}
          leftIconName={"arrow-left"}
          onPressLeftIcon={() => this.props.navigation.goBack()}
          pageTitleColor={"white"}
          leftIconColor={"white"}
        />
        <ScrollView>
          <View style={{ flex: 1, paddingHorizontal: 20, paddingTop: 15 }}>
            <View>
              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .assessment_title
                }
              </Text>
              <Text style={styles.desc}>{assessment.title}</Text>
              <View style={styles.line(false)} />

              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .schema_label
                }
              </Text>
              <Text style={styles.desc}>{assessment.schema_label}</Text>
              <View style={styles.line(false)} />

              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .assessment_date
                }
              </Text>
              <Text style={styles.desc}>
                {Moment(assessment.start_date).format("DD MMMM YYYY")}
              </Text>
              <View style={styles.line(false)} />

              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .assessment_address
                }
              </Text>
              <Text style={styles.desc}>{assessment.address}</Text>
              <View style={styles.line(false)} />

              <Text style={styles.title}>
                {
                  constants.MULTILANGUAGE(this.props.settings.bahasa)
                    .assessment_note
                }
              </Text>
              <Text style={styles.desc}>{assessment.notes}</Text>
              <View style={styles.line(false)} />
            </View>

            <Text style={styles.title}>
              {constants.MULTILANGUAGE(this.props.settings.bahasa).tuk_name}
            </Text>
            <Text style={styles.desc}>{assessment.tuk_name}</Text>
            <View style={styles.line(true)} />
          </View>
        </ScrollView>
        <View style={{ paddingHorizontal: 20, paddingBottom: 20 }}>
          <Button
            onPressed={() => this._joinSchedule(this.state.assessment_id)}
            title={
              constants.MULTILANGUAGE(this.props.settings.bahasa)
                .join_assessment
            }
            titleColor="white"
          />
        </View>
        <LoadingBar visibility={this.state.show} />
        {/* {this.state.showImage ? <ShowImage /> : null} */}
        <Toast ref="toast" />
      </View>
    );
  }
}

const styles = StyleSheet.create({
  title: {
    marginBottom: 5,
    fontWeight: "bold"
  },
  desc: {
    color: color.black
  },
  line: end => ({
    marginTop: 20,
    borderBottomWidth: 1,
    marginBottom: end == true ? 0 : 20,
    borderBottomColor: color.greyPlaceholder
  })
});

const mapStateToProps = state => ({
  auth: state.auth,
  user: state.user,
  settings: state.settings,
  availableAssessments: state.availableAssessments,
  upn: state.upn
});

const mapDispatchToProps = dispatch => ({
  joinAssessment: (secretkey, username, assessment_id, callback) =>
    dispatch(
      actions.actionsAPI.assessments.joinAssessment(
        secretkey,
        username,
        assessment_id,
        callback
      )
    )
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(JoinSchedule);
