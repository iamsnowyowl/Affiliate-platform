import React, { Component } from "react";
import { connect } from "react-redux";
import { View, Text, Image, Dimensions, TouchableOpacity } from "react-native";
import { IndicatorViewPager, PagerDotIndicator } from "rn-viewpager";
import { StackActions, NavigationActions } from "react-navigation";
import { color } from "../../../styles/color";
import constants from "../../../constants/constants";
import styles from "./styles";
import actions from "../../../actions";

const { width, height } = Dimensions.get("window");

class StepWizard extends Component {
  constructor(props) {
    super(props);
    this.state = {
      images: [
        {
          img: require("../../../assets/image/layout1.png"),
          title: constants.MULTILANGUAGE(this.props.settings.bahasa).title_one,
          text: constants.MULTILANGUAGE(this.props.settings.bahasa).desc_one
        },
        {
          img: require("../../../assets/image/layout2.png"),
          title: constants.MULTILANGUAGE(this.props.settings.bahasa).title_two,
          text: constants.MULTILANGUAGE(this.props.settings.bahasa).desc_two
        },
        {
          img: require("../../../assets/image/layout3.png"),
          title: constants.MULTILANGUAGE(this.props.settings.bahasa)
            .title_three,
          text: constants.MULTILANGUAGE(this.props.settings.bahasa).desc_three
        }
      ]
    };
  }

  _first = () => {
    this.props.firstTime(false, response => response);
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

  render() {
    return (
      <View style={styles.stepWizardContainer}>
        <IndicatorViewPager
          style={{ height: height, paddingTop: 20 }}
          indicator={this._renderDotIndicator()}
        >
          {this.state.images.map((item, index) => {
            currentIndex = index;
            return (
              <View style={{ width: width }} key={index}>
                <Image style={styles.stepWizardImage} source={item.img} />
                <View style={{ height: 20 }} />
                <Text style={styles.stepWizardTitle}>{item.title}</Text>
                <View style={{ height: 30 }} />
                <Text style={styles.stepWizardSubtitle}>{item.text}</Text>
                <View style={{ height: 80 }} />
                {index == this.state.images.length - 1 ? (
                  <TouchableOpacity
                    style={{
                      position: "absolute",
                      bottom: 120,
                      width: width - 40,
                      alignSelf: "center"
                    }}
                    onPress={() => this._first()}
                  >
                    <View style={styles.stepWizardButton}>
                      <Text style={styles.textInButton}>
                        {
                          constants.MULTILANGUAGE(this.props.settings.bahasa)
                            .next
                        }
                      </Text>
                    </View>
                  </TouchableOpacity>
                ) : null}
              </View>
            );
          })}
        </IndicatorViewPager>
      </View>
    );
  }

  _renderDotIndicator() {
    return (
      <PagerDotIndicator
        style={{ marginBottom: 170 }}
        pageCount={this.state.images.length}
        dotStyle={{ backgroundColor: color.greyPlaceholder }}
        selectedDotStyle={{ backgroundColor: color.green }}
      />
    );
  }
}

const mapStateToProps = state => ({
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  firstTime: (data, callback) =>
    dispatch(actions.actionsAPI.settings.firstTime(data, callback))
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(StepWizard);
