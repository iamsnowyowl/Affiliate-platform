import React, { Component } from "react";
import { connect } from "react-redux";
import { View } from "react-native";
import { color } from "../../styles/color";
import Header from "../header/header";
import constants from "../../constants/constants";
import LoadingBar from "../loadingBar/loadingbar";
import WebView from "react-native-webview";

class WebviewPage extends Component {
  constructor(props) {
    super(props);
    this.state = {
      show: true,
      pageName: this.props.navigation.getParam("pageName"),
      url: this.props.navigation.getParam("url")
    };
  }

  render() {
    const { pageName, url } = this.state;
    return (
      <View style={{ flex: 1 }}>
        <Header
          headerColor={color.green}
          leftIconType="icon"
          leftIconName="arrow-left"
          leftIconColor="white"
          onPressLeftIcon={() => this.props.navigation.goBack()}
          pageTitle={pageName}
          pageTitleColor="white"
        />
        <WebView
          onLoad={() => this.setState({ show: url != "" ? false : true })}
          source={{ uri: url }}
        />
        <LoadingBar visibility={this.state.show} />
      </View>
    );
  }
}

const mapStateToProps = state => ({});

const mapDispatchToProps = dispatch => ({});

export default connect(mapStateToProps, mapDispatchToProps)(WebviewPage);
