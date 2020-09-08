"use strict";

class Config {
    constructor() {
        this.modules = new Map();
        this.themes = new Map();
        this.areas = new Map();
        this.targets = [];
    }

    forEachModule(callback) {
        for (const entry of this.modules) {
            const result = callback(entry[1]);

            if (result !== undefined) {
                return result;
            }
        }
    }

    forEachTheme(target, callback) {
        for (let theme = this.themes.get(target.theme); theme != null;
            theme = this.themes.get(theme.parent_theme))
        {
            const result = callback(theme);

            if (result !== undefined) {
                return result;
            }
        }
    }

    forEachThemeDefinition(theme, callback) {
        if (!theme.definitions) {
            return;
        }

        for (const entry of theme.definitions) {
            const result = callback(entry[1]);

            if (result !== undefined) {
                return result;
            }
        }
    }

    forEachArea(target, callback) {
        for (let area = this.areas.get(target.area); area != null; area = this.areas.get(area.parent_area)) {
            const result = callback(area);

            if (result !== undefined) {
                return result;
            }
        }
    }

    isRelevant(target, definition, mostAbstractArea) {
        return this.forEachArea(target, area => {
            if (definition.area === area.name) {
                return true;
            }

            if (mostAbstractArea && area.name == mostAbstractArea) {
                return false;
            }
        });
    }
}

module.exports = new Config();