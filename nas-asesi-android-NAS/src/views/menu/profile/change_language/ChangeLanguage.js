import React, { Component } from "react";
import {
  View,
  Text,
  TouchableWithoutFeedback,
  Dimensions,
  TouchableOpacity
} from "react-native";
import { color } from "../../../../styles/color";
import { connect } from "react-redux";
import actions from "../../../../actions";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";
import Icon from "react-native-vector-icons/AntDesign";

const { width, height } = Dimensions.get("window");

class ChangeLanguage extends Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  _gantiBahasa = bahasa => {
    this.props.gantiBahasa(bahasa, response => response);
  };

  render() {
    return (
      <View style={{ flex: 1, backgroundColor: color.greyWhite }}>
        <Header
          headerColor={color.green}
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).change_language
          }
          pageTitleColor={"white"}
          leftIconType={"icon"}
          leftIconName="arrow-left"
          leftIconColor="white"
          onPressLeftIcon={() => this.props.navigation.goBack()}
        />
        <View style={{ height: 20 }} />
        <View
          style={{
            paddingHorizontal: 20,
            backgroundColor: "white"
          }}
        >
          <TouchableOpacity onPress={() => this._gantiBahasa("en")}>
            <View
              style={{
                paddingVertical: 20,
                borderBottomWidth: 1,
                borderBottomColor: color.greyPlaceholder,
                flexDirection: "row"
              }}
            >
              {this.props.settings.bahasa == "en" ? (
                <Icon name="check" size={20} color={"green"} />
              ) : null}
              <Text
                style={{
                  paddingLeft: this.props.settings.bahasa == "en" ? 5 : 25,
                  fontSize: 16,
                  justifyContent: "center"
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).english}
              </Text>
            </View>
          </TouchableOpacity>
          <TouchableOpacity onPress={() => this._gantiBahasa("id")}>
            <View
              style={{
                paddingVertical: 20,
                borderBottomWidth: 1,
                borderBottomColor: color.greyPlaceholder,
                flexDirection: "row"
              }}
            >
              {this.props.settings.bahasa == "id" ? (
                <Icon name="check" size={20} color={"green"} />
              ) : null}
              <Text
                style={{
                  paddingLeft: this.props.settings.bahasa == "id" ? 5 : 25,
                  fontSize: 16,
                  justifyContent: "center"
                }}
              >
                {constants.MULTILANGUAGE(this.props.settings.bahasa).indonesian}
              </Text>
            </View>
          </TouchableOpacity>
        </View>
      </View>
    );
  }
}

const mapStateToProps = state => ({
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  gantiBahasa: (data, callback) =>
    dispatch(actions.actionsAPI.settings.gantiBahasa(data, callback))
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(ChangeLanguage);
