import React, { Component } from "react";
import { Empty } from "antd";

type Props = {
  label: "any"
};
class EmptyData extends Component<Props> {
  render() {
    return (
      <div>
        <Empty description={<span>{this.props.label}</span>}></Empty>
      </div>
    );
  }
}

export default EmptyData;
