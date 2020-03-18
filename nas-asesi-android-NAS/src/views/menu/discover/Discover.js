import React, { Component } from "react";
import { connect } from "react-redux";
import {
  View,
  Text,
  TouchableOpacity,
  ScrollView,
  Dimensions,
  FlatList
} from "react-native";
import { color } from "../../../styles/color";
import { imgURL } from "../../../assets/image/source";
import Header from "../../../components/header/header";
import constants from "../../../constants/constants";
import ImageBackgroundRow from "../../../components/imageBackgroundRowItem/imgBackgroundRow";
import discoverStyle from "../discover/style";
import actions from "../../../actions/";
import EmptyContainer from "../../../components/emptyContainer/emptyContainer";

const { width, height } = Dimensions.get("window");

class Discover extends Component {
  constructor(props) {
    super(props);
    this.state = {
      // renderList: true
    };
  }

  static navigationOptions = {
    tabBarLabel: constants.MULTILANGUAGE("en").product
  };

  componentDidMount() {
    // this.props.getSchema(
    //   this.props.auth.secret_key,
    //   this.props.auth.data.username,
    //   null,
    //   response => response
    // );
    this.setState({ renderList: false });
  }

  render() {
    return (
      <View style={discoverStyle.container}>
        <Header headerColor="white" title={constants.APP_NAME} />
        {this.props.schemas.length > 0 ? (
          <ScrollView style={{ paddingHorizontal: 20 }}>
            {this.props.schemas.map((item, index) => {
              return (
                <TouchableOpacity
                  key={index}
                  style={{ marginBottom: 10, marginTop: 10 }}
                  onPress={() =>
                    this.props.navigation.navigate("CertificationDetail", {
                      sub_schema_id: item.sub_schema_id,
                      title: item.schema_label
                    })
                  }
                >
                  <ImageBackgroundRow
                    imgSource={imgURL.imageSource}
                    titleItemRow={item.schema_label}
                    descItemRow={item.sub_schema_number}
                  />
                </TouchableOpacity>
              );
            })}
          </ScrollView>
        ) : (
          <View style={{ height: height - 110, justifyContent: "center" }}>
            <EmptyContainer
              emptyText={
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .empty_product
              }
            />
          </View>
        )}
      </View>
    );
  }
}

const mapStateToProps = state => ({
  auth: state.auth,
  schemas: state.schemas,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  getSchema: (secretkey, username, data, callback) =>
    dispatch(
      actions.actionsAPI.discover.getSchema(secretkey, username, data, callback)
    )
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Discover);
