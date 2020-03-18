import React, { Component } from 'react';
import { View, Text, ScrollView } from 'react-native';

const SCROLLVIEW_REF = 'scrollview';

type Props = {
  childrenCount: any,
  interval: any,
  position: any
};
export default class AutoScrollView extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      width: 0,
      autoPlay: true
    };
    this._goToNextPage = this._goToNextPage.bind(this);
    this._onScroll = this._onScroll.bind(this);
    this._startAutoPlay = this._startAutoPlay.bind(this);
    this._stopAutoPlay = this._stopAutoPlay.bind(this);
    this._onScrollViewLayout = this._onScrollViewLayout.bind(this);

    this._currentIndex = 0;
    this._childrenCount = this.props.childrenCount;
  }

  componentDidMount() {
    if (this.state.autoPlay) this._startAutoPlay();
    else this._stopAutoPlay();
  }

  render() {
    const { children, position } = this.props;
    return (
      <ScrollView
        style={{ position: position }}
        horizontal={true}
        ref={SCROLLVIEW_REF}
        onLayout={this._onScrollViewLayout}
        onScroll={this._onScroll}
        pagingEnabled={true}
        scrollEventThrottle={8}
        showsHorizontalScrollIndicator={false}
      >
        {children}
      </ScrollView>
    );
  }

  _onScroll(event) {
    let { x } = event.nativeEvent.contentOffset,
      offset,
      position = Math.floor(x / this.state.width);
    if (x == this._preScrollX) return;
    this._preScrollX = x;
    offset = x / this.state.width - position;

    if (offset == 0) {
      this._currentIndex = position;
      this._timer = setInterval(this._goToNextPage, this.props.interval);
    }
  }

  _onScrollViewLayout(event) {
    let { width } = event.nativeEvent.layout;
    this.setState({ width: width });
  }

  _goToNextPage() {
    this._stopAutoPlay();
    let nextIndex = (this._currentIndex + 1) % this._childrenCount;
    this.refs[SCROLLVIEW_REF].scrollTo({ x: this.state.width * nextIndex });
  }

  _startAutoPlay() {
    this._timer = setInterval(this._goToNextPage, this.props.interval);
  }

  _stopAutoPlay() {
    if (this._timer) {
      clearInterval(this._timer);
      this._timer = null;
    }
  }
}
