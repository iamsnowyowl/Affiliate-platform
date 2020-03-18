import React, { Component } from "react";
import {
  View,
  Text,
  Platform,
  Animated,
  Dimensions,
  StyleSheet,
  ScrollView,
  RefreshControl,
  TouchableOpacity,
  ActivityIndicator
} from "react-native";
import { connect } from "react-redux";
import { color } from "../../../../styles/color";
import SearchForm from "../../../../components/formWithIcon/formWithIcon";
import globalStyle from "../../../../styles/index";
import actions from "../../../../actions";
import EmptyContainer from "../../../../components/emptyContainer/emptyContainer";
import Header from "../../../../components/header/header";
import constants from "../../../../constants/constants";
import LoadingBar from "../../../../components/loadingBar/loadingbar";

const { width, height } = Dimensions.get("screen");
class CertificationScheme extends Component {
  constructor(props) {
    super(props);
    this.state = {
      refreshing: false,
      loadMore: false,
      search: "",
      offset: 0,
      count: 0,
      position: 1,
      schema: [],
      visible: false
    };
    this.moveAnimation = new Animated.ValueXY({ x: 0, y: 0 });
  }

  componentDidMount() {
    this.getSchema();
  }

  getSchema() {
    this.state.loadMore == true ? null : this.setState({ visible: true });
    this.props.getSchema("", 10, this.state.offset, response => {
      this.setState({ visible: false });
      let schemas = [...this.state.schema, ...response.data.data];
      this.setState({
        schema: schemas,
        count: response.data.count,
        loadMore: false
      });
    });
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
    this.props.getSchema(keyword, 10, this.state.offset, response =>
      this.setState({ schema: response.data.data })
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

  isCloseToBottom = ({ layoutMeasurement, contentOffset, contentSize }) => {
    const paddingToBottom = 5;
    return (
      layoutMeasurement.height + contentOffset.y >=
      contentSize.height - paddingToBottom
    );
  };

  render() {
    let { schema, count, offset } = this.state;
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
            constants.MULTILANGUAGE(this.props.settings.bahasa).schema_label
          }
          onPressLeftIcon={() => this.props.navigation.goBack()}
          onPressRightIcon={() => this._showSearchBar()}
        />
        {schema.length > 0 ? (
          <ScrollView
            onScroll={({ nativeEvent }) => {
              if (this.isCloseToBottom(nativeEvent)) {
                if (offset < count) {
                  let newOffset = offset + 10;
                  if (newOffset < count) {
                    new Promise((res, rej) => {
                      this.setState({ offset: newOffset, loadMore: true });
                      res("ok");
                    }).then(() => {
                      this.getSchema();
                    });
                  }
                }
              }
            }}
            refreshControl={this._refreshControl()}
            showsVerticalScrollIndicator={false}
            style={{
              alignSelf: "center",
              paddingTop: this.state.position == 1 ? 20 : 60,
              zIndex: -1
            }}
          >
            {schema.map((item, index) => {
              return (
                <TouchableOpacity
                  key={index}
                  style={{
                    marginBottom: index == schema.length - 1 ? 40 : 15,
                    paddingHorizontal: 20
                  }}
                  onPress={() =>
                    this.props.navigation.navigate("JoinScheme", {
                      schema: item
                    })
                  }
                >
                  <View style={styles.container}>
                    <View style={{ width: width - 60 }}>
                      <Text
                        style={globalStyle.text(14, 5, "bold", color.black)}
                      >
                        {item.sub_schema_number}
                      </Text>
                      <Text
                        style={globalStyle.text(
                          14,
                          0,
                          "normal",
                          color.darkGrey
                        )}
                      >
                        {item.sub_schema_name}
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
        {this.state.loadMore == true ? (
          <ActivityIndicator
            style={{ alignSelf: "center", position: "absolute", bottom: 15 }}
            color={color.green}
            size="large"
          />
        ) : null}
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
  upn: state.upn
});

const mapDispatchToProps = dispatch => ({
  getSchema: (keyword, limit, offset, callback) => {
    dispatch(
      actions.actionsAPI.discover.getSchema(keyword, limit, offset, callback)
    );
  }
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(CertificationScheme);
