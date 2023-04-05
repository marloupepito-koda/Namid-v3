<template>
    <v-btn icon>
        <v-tooltip text="Click to Upload" activator="parent" location="bottom">
            <template v-slot:activator="{ props }"> </template>
        </v-tooltip>
        <v-icon size="x-large">mdi-cloud-upload-outline</v-icon>
    </v-btn>
</template>
