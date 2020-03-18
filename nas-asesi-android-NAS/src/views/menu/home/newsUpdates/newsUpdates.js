import React, { Component } from "react";
import {
  View,
  Platform,
  Animated,
  StyleSheet,
  ScrollView,
  Dimensions,
  RefreshControl,
  TouchableOpacity
} from "react-native";
import { color } from "../../../../styles/color";
import { connect } from "react-redux";
import { imgURL } from "../../../../assets/image/source";
import SearchForm from "../../../../components/formWithIcon/formWithIcon";
import NewsItem from "../../../../components/newsItem/newsItem";
import EmptyContainer from "../../../../components/emptyContainer/emptyContainer";
import actions from "../../../../actions";
import LoadingBar from "../../../../components/loadingBar/loadingbar";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";

const { width, height } = Dimensions.get("window");

class NewsUpdates extends Component {
  constructor(props) {
    super(props);
    this.state = {
      refreshing: false,
      search: "",
      visible: false,
      position: 1,
      articles: []
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
    this.props.searchArticles(keyword, response =>
      this.setState({ articles: response.data.data, visible: false })
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
            constants.MULTILANGUAGE(this.props.settings.bahasa).new_updates
          }
          onPressLeftIcon={() => this.props.navigation.goBack()}
          onPressRightIcon={() => this._showSearchBar()}
        />
        {this.state.articles.length > 0 ? (
          <ScrollView
            refreshControl={this._refreshControl()}
            style={{
              paddingTop: this.state.position == 1 ? 20 : 50,
              zIndex: 1
            }}
          >
            {this.state.articles.map((item, index) => {
              return (
                <TouchableOpacity
                  key={index}
                  style={{
                    margin: 15,
                    marginBottom:
                      index == this.state.articles.length - 1 ? 40 : 10
                  }}
                  onPress={() =>
                    this.props.navigation.navigate("DetailNews", {
                      article_id: item.article_id
                    })
                  }
                >
                  <NewsItem
                    newsImg={constants.URL + item.media}
                    newsTitle={item.title}
                    newsTime={item.created_date}
                  />
                </TouchableOpacity>
              );
            })}
          </ScrollView>
        ) : (
          <View style={{ justifyContent: "center", flex: 1 }}>
            <EmptyContainer
              emptyText={
                constants.MULTILANGUAGE(this.props.settings.bahasa).empty_news
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
  }
});

const mapStateToProps = state => ({
  auth: state.auth,
  articles: state.articles,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  searchArticles: (data, callback) =>
    dispatch(actions.actionsAPI.articles.searchArticles(data, callback))
});

export default connect(mapStateToProps, mapDispatchToProps)(NewsUpdates);
