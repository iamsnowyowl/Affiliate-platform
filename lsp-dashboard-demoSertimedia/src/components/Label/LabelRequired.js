import React, { Component } from "react";
import { Label } from "reactstrap";

type Props = {
  label: any,
  fors: any
};

class LabelRequired extends Component<Props> {
  render() {
    const { label, fors } = this.props;
    return (
      <div style={{ marginTop: "6px" }}>
        <Label for={fors}>
          {label}
          <span className="required">*</span>
        </Label>
      </div>
    );
  }
}

export default LabelRequired;
