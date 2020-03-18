import React, { Component } from "react";

import { multiLanguage } from "../Language/getBahasa";

type Props = {
  url: any,
  type: String
};

class ButtonEdit extends Component<Props> {
  render() {
    const { url, type } = this.props;
    return type === "edit" ? (
      <a
        href={url}
        className="btn btn-success col-md-auto"
        title={multiLanguage.Edit}
      >
        <i className="fa fa-edit"> </i>{" "}
      </a>
    ) : type === "assign" ? (
      <a href={url} className="btn btn-success" title="Assign">
        <i className="fa fa-users" />
      </a>
    ) : (
      ""
    );
  }
}

export default ButtonEdit;
