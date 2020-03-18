import React, { Component } from "react";
import { color } from "../../styles/color";
import { View, Text, ScrollView, Dimensions } from "react-native";
import Modal from "react-native-modal";

const { height } = Dimensions.get("screen");

type Props = {
  title: String,
  description: String
  // can_close: boolean
};
export default class BottomSheet extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      onShow: false
    };
  }

  open() {
    this.setState({ onShow: true });
  }

  close() {
    this.setState({ onShow: false });
  }

  handleOnScroll = event => {
    this.setState({
      scrollOffset: event.nativeEvent.contentOffset.y
    });
  };

  handleScrollTo = p => {
    if (this.scroll) {
      this.scroll.scrollTo(p);
    }
  };

  render() {
    const { title, children, description } = this.props;
    return (
      <Modal
        isVisible={this.state.onShow}
        onBackdropPress={this.close.bind(this)}
        onBackButtonPress={this.close.bind(this)}
        onSwipeComplete={this.close.bind(this)}
        swipeDirection="down"
        scrollTo={this.handleScrollTo}
        scrollOffset={this.state.scrollOffset}
        scrollOffsetMax={height - (height - 100)} // content height - ScrollView height
        style={{ justifyContent: "flex-end", margin: 0 }}
      >
        <View
          style={{
            height: height - 100,
            backgroundColor: color.greyPlaceholder,
            borderTopRightRadius: 8,
            borderTopLeftRadius: 8
          }}
        >
          <ScrollView
            ref={ref => (this.scroll = ref)}
            onScroll={this.handleOnScroll}
            scrollEventThrottle={16}
          >
            <View style={{ margin: 15 }}>
              <Text
                style={{
                  fontSize: 16,
                  color: "black",
                  fontWeight: "bold",
                  textAlign: "center"
                }}
              >
                {title}
              </Text>
              <View style={{ marginBottom: 10 }} />
              {description != "" ? (
                <Text style={{ textAlign: "center", color: "black" }}>
                  {description}
                </Text>
              ) : null}
              <View>{children}</View>
            </View>
          </ScrollView>
        </View>
      </Modal>
    );
  }
}
