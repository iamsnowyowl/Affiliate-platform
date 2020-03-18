import React, { Component } from 'react';
import { View, Text } from 'react-native';

type Props = {
  style: any,
  size: Int,
  color: any,
  value: any,
  valueColor: any
};
export default class Badge extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    const { style, size, color, value, valueColor } = this.props;
    return (
      <View style={style}>
        <Text
          style={{
            width: size,
            height: size,
            backgroundColor: color,
            borderRadius: 20,
            textAlign: 'center',
            color: valueColor,
            alignSelf: 'center',
            fontWeight: 'bold'
          }}
        >
          {value}
        </Text>
      </View>
    );
  }
}
