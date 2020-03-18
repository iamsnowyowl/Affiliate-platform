import React, { Component } from "react";
import {
  View,
  Text,
  ImageBackground,
  TouchableOpacity,
  Platform,
  ScrollView,
  Dimensions
} from "react-native";
import { connect } from "react-redux";
import { color } from "../../../../../styles/color";
import Header from "../../../../../components/header/header";
import constants from "../../../../../constants/constants";
import path from "../../../../../constants/path";
import LoadingBar from "../../../../../components/loadingBar/loadingbar";
import WebView from "react-native-webview";

const { width, height } = Dimensions.get("window");

class DetailNews extends Component {
  constructor(props) {
    super(props);
    this.state = {
      tags: [
        "#NAS",
        "#BNSP",
        "#LSP",
        "#Sertifikasi",
        "#LembagaSertifikasi",
        "#Certificate",
        "#Certification"
      ],
      article: {},
      show: true
    };
  }

  componentWillMount() {
    // let article_id = this.props.navigation.getParam('article_id');
    // let detail = this.props.articles.find(function(element) {
    //   return element.article_id == article_id;
    // });
    // this.setState({ article: detail });
  }

  render() {
    return (
      <View style={{ flex: 1 }}>
        <Header
          headerColor={color.green}
          leftIconName={"arrow-left"}
          leftIconType={"icon"}
          leftIconColor={"white"}
          onPressLeftIcon={() => this.props.navigation.goBack()}
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).new_updates
          }
          pageTitleColor={"white"}
        />
        <WebView
          onLoad={() => this.setState({ show: false })}
          source={{
            uri:
              constants.URL.replace(/api-/, "") +
              "/#" +
              path.article(this.props.navigation.getParam("article_id"))
          }}
        />
        <LoadingBar visibility={this.state.show} />
      </View>
    );
  }
}

const mapStateToProps = state => ({
  articles: state.articles,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(DetailNews);
