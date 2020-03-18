import React, { Component } from 'react';
import { Dimensions, Platform } from 'react-native';
import { View, Text, TouchableOpacity } from 'react-native';
import buttonStyle from '../buttonWithIcon/style';
import Icon from 'react-native-vector-icons/FontAwesome5';
import { color } from '../../styles/color';

const { width, height } = Dimensions.get('window');

type Props = {
  title: String,
  iconName: String,
  backgroundColor: any
};

export default class ButtonWithIcon extends Component<Props> {
  render() {
    const { title, iconName, backgroundColor } = this.props;
    return (
      <View
        style={{
          width: 80,
          // backgroundColor: '#a7adba',
          justifyContent: 'center',
          alignItems: 'center'
        }}
      >
        <View style={buttonStyle.container(backgroundColor)}>
          <Icon name={iconName} size={25} color={color.white} />
        </View>
        <Text style={buttonStyle.text}>{title}</Text>
      </View>
    );
  }
}
