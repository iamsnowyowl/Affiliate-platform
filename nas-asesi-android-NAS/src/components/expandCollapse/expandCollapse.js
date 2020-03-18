import React, { Component } from "react";
import {
  View,
  Text,
  Animated,
  TouchableHighlight,
  StyleSheet
} from "react-native";
import { color } from "../../styles/color";
import Icon from "react-native-vector-icons/FontAwesome";

type Props = {
  title?: String,
  childrenBackground: any
};

export default class ExpandCollapse extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      expanded: false,
      animation: new Animated.Value(1)
    };
  }

  componentDidMount() {
    this.state.animation.setValue(
      !this.state.expanded ? 62 : this.state.maxHeight
    );
  }

  toggle() {
    let initialValue = this.state.expanded
        ? this.state.maxHeight + this.state.minHeight
        : this.state.minHeight,
      finalValue = this.state.expanded
        ? this.state.minHeight
        : this.state.maxHeight + this.state.minHeight;

    this.setState({
      expanded: !this.state.expanded
    });

    this.state.animation.setValue(initialValue);
    Animated.spring(this.state.animation, {
      toValue: finalValue
    }).start();
  }

  _setMaxHeight(event) {
    this.setState({
      maxHeight: event.nativeEvent.layout.height
    });
  }

  _setMinHeight(event) {
    this.setState({
      minHeight: event.nativeEvent.layout.height
    });
  }

  render() {
    const { title, children, childrenBackground } = this.props;
    return (
      <Animated.View
        style={[styles.container, { height: this.state.animation }]}
      >
        <TouchableHighlight
          style={styles.button}
          onPress={() => this.toggle()}
          underlayColor="#f1f1f1"
        >
          <View
            style={styles.titleContainer}
            onLayout={this._setMinHeight.bind(this)}
          >
            <Text style={styles.title}>{title}</Text>
            <Icon
              style={{ paddingVertical: 20 }}
              name={this.state.expanded == true ? "chevron-up" : "chevron-down"}
              color={"black"}
              size={20}
            />
          </View>
        </TouchableHighlight>
        <View
          style={{
            backgroundColor: childrenBackground
              ? childrenBackground
              : color.greyWhite,
            padding: 10,
            paddingTop: 5,
            borderBottomRightRadius: 10,
            borderBottomLeftRadius: 10
          }}
          onLayout={this._setMaxHeight.bind(this)}
        >
          {children}
        </View>
      </Animated.View>
    );
  }
}

var styles = StyleSheet.create({
  container: {
    backgroundColor: "white",
    overflow: "hidden"
  },
  titleContainer: {
    flexDirection: "row",
    borderBottomWidth: 1,
    borderBottomColor: color.greyPlaceholder
  },
  title: {
    flex: 1,
    paddingVertical: 20,
    color: "#000",
    fontWeight: "bold"
  },
  button: {
    justifyContent: "center"
  },
  buttonImage: {
    width: 30,
    height: 25
  }
});
