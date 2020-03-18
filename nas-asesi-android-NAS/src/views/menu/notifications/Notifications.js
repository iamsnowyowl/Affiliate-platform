import React, { Component } from "react";
import { View, Text, ScrollView, TouchableOpacity } from "react-native";
import { connect } from "react-redux";
import { color } from "../../../styles/color";
import actions from "../../../actions";
import EmptyContainer from "../../../components/emptyContainer/emptyContainer";
import Header from "../../../components/header/header";
import constants from "../../../constants/constants";
import Badge from "../../../components/badge/Badge";
import Icon from "react-native-vector-icons/FontAwesome";

class Notifications extends Component {
  constructor(props) {
    super(props);
    this.state = {
      notifications: []
    };
  }

  componentDidMount() {
    this._getNotifications();
  }

  _getNotifications() {
    this.props.getNotification(
      this.props.auth.secret_key,
      this.props.upn,
      null,
      response => this.setState({ notifications: response.data.data })
    );
  }

  render() {
    return (
      <View style={{ flex: 1, backgroundColor: color.greyWhite }}>
        <Header
          headerColor={color.green}
          pageTitle={
            constants.MULTILANGUAGE(this.props.settings.bahasa).notifications
          }
          pageTitleColor="white"
          leftIconType="icon"
          leftIconName="arrow-left"
          leftIconColor="white"
          onPressLeftIcon={() => this.props.navigation.goBack()}
        />
        {this.state.notifications.length > 0 ? (
          <ScrollView style={{ paddingTop: 20 }}>
            {this.state.notifications.map((item, index) => {
              return (
                <TouchableOpacity key={index}>
                  <View
                    style={{
                      borderBottomWidth: 1,
                      borderTopWidth: 1,
                      borderBottomColor: color.greyPlaceholder,
                      borderTopColor: color.greyPlaceholder,
                      paddingHorizontal: 20,
                      flexDirection: "row",
                      backgroundColor: color.white,
                      alignItems: "center",
                      height: 50
                    }}
                  >
                    <Icon name="newspaper-o" size={25} color="black" />
                    {item.count > 0 ? (
                      <Badge
                        style={{ position: "absolute", left: 38, top: 3 }}
                        size={20}
                        color="red"
                        valueColor="white"
                        value={item.count}
                      />
                    ) : null}
                    <View style={{ width: 15 }} />
                    <Text
                      style={{
                        fontWeight: "bold",
                        color: "black",
                        justifyContent: "center"
                      }}
                    >
                      {/* {item.cluster_name.replace(/_/g, ' ')} */}
                      {item.title}
                    </Text>
                  </View>
                </TouchableOpacity>
              );
            })}
          </ScrollView>
        ) : (
          <View
            style={{
              flex: 1,
              backgroundColor: "white",
              justifyContent: "center"
            }}
          >
            <EmptyContainer
              emptyText={
                constants.MULTILANGUAGE(this.props.settings.bahasa)
                  .empty_notifications
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
  upn: state.upn,
  settings: state.settings
});

const mapDispatchToProps = dispatch => ({
  getNotification: (secretkey, username, data, callback) =>
    dispatch(
      actions.actionsAPI.notifications.getNotification(
        secretkey,
        username,
        data,
        callback
      )
    )
});

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Notifications);
