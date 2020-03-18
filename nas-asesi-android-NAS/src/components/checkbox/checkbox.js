import React, { Component } from 'react';
import { View, Text, TouchableOpacity } from 'react-native';
import { color } from '../../styles/color';
import Icon from 'react-native-vector-icons/Ionicons';
import checkboxStyle from '../checkbox/style';

type Props = {
  check: any,
  checkIconColor: any,
  checkboxTitle: any,
  checkboxBoldTitle: any
};
export default class Checkbox extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = { checked: false };
  }
  render() {
    const {
      check,
      checkIconColor,
      checkboxTitle,
      checkboxBoldTitle
    } = this.props;
    return (
      <View
        style={{
          paddingRight: 20,
          flexDirection: 'row'
        }}
      >
        {check == '0' ? (
          <Icon name="md-square-outline" size={27} color={checkIconColor} />
        ) : (
          <Icon name="md-checkbox" size={27} color={checkIconColor} />
        )}
        <Text numberOfLines={2} style={checkboxStyle.title}>
          {checkboxTitle}
          <Text style={{ fontWeight: 'bold' }}> {checkboxBoldTitle} </Text>
        </Text>
      </View>
    );
  }
}
